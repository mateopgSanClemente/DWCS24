<?php
    /**
     * Establece una conexión a la base de datos MySQL utilizando las credenciales de las variables de entorno.
     * 
     * Esta función intenta conectar con la base de datos usando las credenciales definidas en las
     * variables de entorno. Si la base de datos no existe, la función no genera un error, sino
     * que permite seguir trabajando con la conexión para que se pueda crear la base de datos
     * en un paso posterior si fuera necesario.
     * 
     * @return array Devuelve un array con la siguiente información:
     *   - 'success' (bool): Indica si la conexión fue exitosa o no.
     *   - 'conexion' (mysqli|null): El objeto de conexión mysqli si fue exitosa, null en caso contrario.
     *   - 'error' (string): Mensaje de error si la conexión falló, vacío si fue exitosa.
     */
    function conectar_mysqli() {   
        try {
            // Obtener las credenciales de la base de datos desde las variables de entorno
            $host = getenv("MYSQL_HOST") ?: "db"; // "db" es el nombre del servicio en docker-compose, la variable de entorno MYSQL_HOST no está configurada
            $user = getenv("MYSQL_USER_WEB") ?: "root";
            $password = getenv("MYSQL_PASSWORD_WEB") ?: "test";
            $db = getenv("MYSQL_DATABASE_TAREA_UD4") ?: ""; // Puede que la base de dato no haya sido creada todavía. Si no está configurada se guarda una cadena vacía en la variable.
            
            // Configurar mysqli para lanzar excepciones automáticamente y no tener que usar connect_errno manualmente. Evita errores silenciosos.
            // https://www.php.net/manual/en/mysqli-driver.report-mode.php
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            
            // Crear la conexión
            $conexion_mysqli = new mysqli($host, $user, $password);

            // Asegurar una codificación adecuada de caracteres
            $conexion_mysqli->set_charset("utf8mb4");

            // Compruebo que la base de datos existe
            $sql_check = "SHOW DATABASE LIKE ?;";
            $stmt = $conexion_mysqli->prepare($sql_check);
            $stmt->bind_param("s", $db);
            $stmt->execute();

            $comprobar_db = $stmt->get_result();

            if ($comprobar_db && $comprobar_db->num_rows > 0) {
                // En caso de que exista, me conecto a esta base de datos
                $conexion_mysqli->select_db($db);
            }

            // Devolver la instancia del objeto mysqli en caso de que no exista la base de datos
            return ["success" => true, "conexion" => $conexion_mysqli, "error" => ""];
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "conexion" => null, "error" => $e->getMessage()];
        }
    }

    /**
     * Crea la base de datos 'tareas' si no existe.
     *
     * Esta función utiliza la sentencia `CREATE DATABASE IF NOT EXISTS` para asegurarse de que 
     * la base de datos no se cree si ya está definida en el servidor.
     *
     * @param mysqli $conexion_mysqli Objeto de conexión a MySQL.
     * @return array Devuelve un array con la siguiente información: 
     *               - 'success' (bool): Indica si la base de datos se creó correctamente.
     *               - 'mensaje' (string): Mensaje de éxito o error.
     */ 
    function crear_base_datos (mysqli $conexion_mysqli) {
        try {
            // Comprobar que la base de datos no existe
            /* La siguiente comprobación no es necesaria ya que se usa la sentencia CREATE DATA BASE IF NOT EXISTS, 
             * que ya evita problemas en caso de que la base de datos no exista, de todas formas muestra
             * de forma más amigable la información en caso de que la tabla 'tareas' ya exista.
             */
             $sql_check = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'tareas'"; // También se podría utilizar la sentencia SQL: SHOW DATABASE LIKE base_datos
            
            $resultado_comprobacion = $conexion_mysqli->query($sql_check);

            if ($resultado_comprobacion && $resultado_comprobacion->num_rows > 0)
            {
                return ["success" => false, "mensaje" => "La base de datos 'tareas' ya existe."];
            }
            

            $sql = "CREATE DATABASE IF NOT EXISTS `tareas` DEFAULT CHARACTER SET utf8mb4;";
            
            if ($conexion_mysqli->query($sql)) {
                return ["success" => true, "mensaje" => "Base de datos 'tareas' creada correctamente."];
            }

            // Si la consulta falla devuelve un mensaje de error, pero realmente llega a ejecutarse esta línea??
            return ["success" => false, "mensaje" => "No se pudo crear la base de datos: " . $conexion_mysqli->error];
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "mensaje" => $e->getMessage()];
        }
    }

    /**
     * Cierra la conexión con la base de datos.
     * @param mysqli objeto mysqli que representa la conexión a la base de datos.
     */
    function cerrar_conexion (mysqli $conexion_mysqli) {
        if (isset($conexion_mysqli))
        {
            $conexion_mysqli -> close();
        }
    }

    /**
     * Crea una tabla llamada 'usuarios' si no existe.
     *
     * Esta función verifica si la tabla 'usuarios' ya existe en la base de datos mediante la instrucción
     * `SHOW TABLES`. Si la tabla no existe, intenta crearla con las columnas especificadas.
     * Si ya existe o si ocurre un error durante la creación, retorna un mensaje adecuado.
     *
     * @param mysqli $conexion Objeto de conexión a MySQL.
     * 
     * @return array Devuelve un array con la siguiente información:
     *               - 'success' (bool): Indica si la tabla se creó correctamente.          
     *               - 'mensaje' (string): Mensaje de éxito o error.
     */
    function crear_tabla_usuario (mysqli $conexion_mysqli) {
        try {
            //Verificar si la tabla ya existe
            $sql_check = "SHOW TABLES LIKE usuarios;";
            $resultado = $conexion_mysqli->query($sql_check);

            if ($resultado && $resultado->num_rows > 0) {
                return ["success" => false, "mensaje" => "La tabla 'usuarios' ya existe."];
            }

            $sql = "CREATE TABLE IF NOT EXISTS `tareas`.`usuarios` (
                `id` INT(6) NOT NULL AUTO_INCREMENT,
                `username` VARCHAR(50) NOT NULL,
                `nombre` VARCHAR(50) NOT NULL,
                `apellidos` VARCHAR(100) NOT NULL,
                `contrasena` VARCHAR(100) NOT NULL,
                CONSTRAINT pk_usuarios PRIMARY KEY (`id`)
                );";
        
            if ($conexion_mysqli->query($sql))
            {
                return ["success" => true, "mensaje" => "La tabla 'usuarios' se creo correctamente."];
            }
            else
            {
                return ["success" => false, "mensaje" => "No fue posible crear la tabla 'usuarios'."];
            }
        }
        catch (mysqli_sql_exception $e) {
            return ["success" => false, "mensaje" => $e->getMessage()];
        }
    }

    /**
     * Crea una tabla llamada 'tareas' si no existe.
     *
     * Esta función verifica si la tabla 'tareas' ya existe en la base de datos mediante la instrucción
     * `SHOW TABLES`. Si la tabla no existe, intenta crearla con las columnas especificadas.
     * Si ya existe o si ocurre un error durante la creación, retorna un mensaje adecuado.
     *
     * @param mysqli $conexion Objeto de conexión a MySQL.
     * 
     * @return array Retorna un array donde:
     *               - El primer elemento es un booleano: `true` si la tabla se creó correctamente,
     *                 o `false` si ya existe o ocurrió un error.
     *               - El segundo elemento es un mensaje descriptivo del resultado.
     */
    function crear_tabla_tareas ($conexion)
    {
        try
        {   
            //Comprobar si la tabla 'tareas' ya existe
            $sql_check = "SHOW TABLES LIKE 'tareas';";
            $resultado = $conexion->query($sql_check);

            if($resultado && $resultado->num_rows > 0)
            {
                return [false, "La tabla <b>'tareas'</b> ya existe"];
            }

            //Crear la tabla tareas y vincularla a la tabla usuarios mediante una clave foranea
            $sql = "CREATE TABLE IF NOT EXISTS `tareas`.`tareas` (
                `id` INT(6) AUTO_INCREMENT,
                `titulo` VARCHAR(50) NOT NULL,
                `descripcion` VARCHAR(250),
                `estado` VARCHAR(50),
                `id_usuario` INT,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id`)
            );";

            if ($conexion->query($sql) === true)
            {
                return [true, "La tabla <b>'tareas'</b> se creo correctamente."];
            }
            else
            {
                return [false, "No fue posible crear la tabla <b>'tareas'</b>."];
            }

        }
        catch(mysqli_sql_exception $e)
        {
            return [false, $e->getMessage()];
        }
    }

    /**
     * Obtiene todas las tareas de la tabla `tareas`.
     * 
     * Esta función ejecuta una consulta SQL para seleccionar todas las filas 
     * de la tabla `tareas` y devuelve los resultados como un array asociativo.
     * 
     * @param mysqli $conexion Instancia de la conexión MySQLi.
     * 
     * @return array Un array que contiene:
     *               - bool: `true` si la operación fue exitosa, `false` si hubo un error.
     *               - mixed: Un array asociativo con los resultados si fue exitoso, 
     *                        o un mensaje de error si falló.
     */
    function seleccionar_tareas($conexion)
    {
        try
        {
            // Consulta SQL para seleccionar todas las tareas
            $sql = "SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, usuarios.username
            FROM tareas
            INNER JOIN usuarios
            ON tareas.id_usuario = usuarios.id;";

            $resultados = $conexion->query($sql);

            // Verificar si la consulta devolvió resultados
            if ($resultados->num_rows == 0) {
                return [false, "No se encontraron tareas en la base de datos."];
            }

            // Retornar los resultados como un array asociativo y lo decodifica
            $conjunto_tareas = $resultados->fetch_all(MYSQLI_ASSOC);
            foreach($resultados as $tarea)
            {
                foreach($tarea as $dato_tarea)
                {
                    $dato_tarea = htmlspecialchars_decode($dato_tarea);
                }
            }
            return [true, $conjunto_tareas];
        }
        catch (mysqli_sql_exception $e)
        {
            // Capturar errores y retornar un mensaje claro
            return [false, "Error al obtener las tareas: " . $e->getMessage()];
        }
    }

    /**
     * Función para agregar tareas
     */
    function agregar_tarea($conexion, $titulo, $descripcion, $estado, $id_usuario)
    {
        try
        {   
            //Preparar la consulta
            $conexion->store_result();
            $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, estado, id_usuario) VALUES (?,?,?,?)");
            $stmt->bind_param("sssi", $titulo, $descripcion, $estado, $id_usuario);
            $stmt->execute();
            //Cerrar conexión
            

            return [true, ("La tarea '$titulo' con estado '$estado' se agregó correctamente.")];
        }
        catch (mysqli_sql_exception $e)
        {
            return [false, ("Error a la hora de agregar la tarea: " . $e->getMessage())];
        }
    }

    /**
     * Función para modificar usuario
     */
    function modificar_tarea($conexion, $id_tarea, $titulo, $descripcion, $estado, $id_usuario)
    {
        try
        {
            //Crear consulta preparada
            $sql = "UPDATE tareas
                    SET titulo = ?,
                    descripcion = ?,
                    estado = ?,
                    id_usuario = ?
                    WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssii", $titulo, $descripcion, $estado, $id_usuario, $id_tarea);
            $stmt->execute();

            return [true, ("La tarea '$titulo' con estado '$estado' se modificó correctamente.")];
        }
        catch (mysqli_sql_exception $e)
        {
            return [false, ("Error a la hora de agregar la tarea: " . $e->getMessage())];
        }
    }

    /**
     * Selecciona una tarea por su id
     */
    function seleccionar_tarea_id($conexion, $id_tarea)
    {
        try
        {
            //Consulta sql para selecionar una tarea por su id
            $sql = "SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, usuarios.username
            FROM tareas
            INNER JOIN usuarios
            ON tareas.id_usuario = usuarios.id
            WHERE tareas.id = ?;";

            //Consulta preparada
            $stmt = $conexion->prepare($sql);

            //Vincular parámetro
            $stmt->bind_param("i", $id_tarea);
            $stmt->execute();

            //Obtener resultado
            $resultado = $stmt->get_result();

            //Verificar resultados
            if($resultado->num_rows > 0)
            {
                return [true, $resultado->fetch_assoc()];
            }
            else
            {
                return [false, "No se encontró ninguna tarea con ID $id_tarea."];
            }
        }
        catch (mysqli_sql_exception $e)
        {
            return [false, "Error: $e"];
        }
    }
    
    /**
     * Función para eliminar tareas
     */
    function eliminar_tarea ($conexion, $id_tarea)
    {
        try {
            //Preparar la sentencia sql para eliminar la tarea
            $sql = "DELETE FROM tareas WHERE id = ?";
            $stmt = $conexion->prepare($sql);
        
            //Vincular parámetros
            $stmt->bind_param("i", $id_tarea);
    
            //Ejecutar la consulta
            $stmt->execute();
    
            // Verificar cuántas filas fueron afectadas
            if ($stmt->affected_rows > 0) {
                return [true, "La tarea con ID $id_tarea se eliminó correctamente."];
            } else {
                return [false, "No se encontró ninguna tarea con ID $id_tarea para eliminar."];
            }
        } catch (mysqli_sql_exception $e) {
            return [false, "Error al eliminar la tarea: " . $e->getMessage()];
        }
    }
?>
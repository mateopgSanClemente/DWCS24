<?php
    /**
     * Establece una conexión a la base de datos MySQL utilizando MySQLi.
     * 
     * @param string $host Dirección del servidor MySQL. Por defecto, "db".
     * @param string $user Nombre de usuario de la base de datos. Por defecto, "root".
     * @param string $pass Contraseña del usuario. Por defecto, "test".
     * @param string $db Nombre de la base de datos. Por defecto, "tareas".
     * 
     * @return mysqli Devuelve una instancia de conexión MySQLi si es exitosa.
     * 
     * @throws Exception Si no se puede establecer la conexión, lanza una excepción con detalles del error.
     */
    function conectar_mysqli($host = "db", $user = "root", $pass = "test", $db = "tareas")
    {
        // Crear la conexión
        $conexion = new mysqli($host, $user, $pass, $db);

        // Comprobar si la conexión fue exitosa
        if ($conexion->connect_errno) {
            throw new Exception("Error al conectar a la base de datos: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
        }

        // Retornar la conexión activa
        return $conexion;
    }

    /**
     * Crea una base de datos llamada 'tareas' si no existe.
     *
     * Esta función verifica si la base de datos 'tareas' ya está creada consultando 
     * el esquema `INFORMATION_SCHEMA`. Si la base de datos no existe, se intenta 
     * crear. Si ya existe o ocurre un error, retorna un mensaje adecuado.
     *
     * @param mysqli $conexion Objeto de conexión a MySQL.
     * 
     * @return array Retorna un array donde:
     *               - El primer elemento es un booleano: `true` si la base de datos se creó correctamente,
     *                 o `false` si ya existe o ocurrió un error.
     *               - El segundo elemento es un mensaje descriptivo del resultado.
     * 
     * @throws mysqli_sql_exception Captura excepciones relacionadas con MySQL.
     */
    function crear_base_datos ($conexion)
    {
        try {
            //Comprobar que la base de datos no existe
            $sql_check = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'tareas'";
            $resultado_comprobacion = $conexion->query($sql_check);

            if ($resultado_comprobacion && $resultado_comprobacion->num_rows > 0)
            {
                return [false, "La base de datos <b>'tareas'</b> ya existe."];
            }

            $sql = "CREATE DATABASE IF NOT EXISTS `tareas`;";

            if ($conexion->query($sql))
            {
                return [true, "Base de datos <b>'tareas'</b> creada correctamente."];
            }
            else
            {
                return [false, "No se pudo crear la base de datos <b>'tareas'</b>."];
            }
            return $base_datos;
        }
        catch (mysqli_sql_exception $e)
        {
            return [false, $e->getMessage()];
        }
    }

    /**
     * Función para cerrar la conexión con la base de datos.
     * @param mysqli objeto mysqli que representa la conexión a la base de datos.
     */
    function cerrar_conexion ($conexion)
    {
        if (isset($conexion))
        {
            $conexion -> close();
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
     * @return array Retorna un array donde:
     *               - El primer elemento es un booleano: `true` si la tabla se creó correctamente,
     *                 o `false` si ya existe o ocurrió un error.
     *               - El segundo elemento es un mensaje descriptivo del resultado.
     * 
     * @throws mysqli_sql_exception Captura excepciones relacionadas con MySQL.
     */
    function crear_tabla_usuario ($conexion)
    {
        try 
        {
            //Verificar si la tabla ya existe
            $sql_check = "SHOW TABLES LIKE 'usuarios';";
            $resultado = $conexion->query($sql_check);

            if ($resultado && $resultado->num_rows > 0)
            {
                return [false, "La tabla <b>'usuarios'</b> ya existe."];
            }

            $sql = "CREATE TABLE IF NOT EXISTS `tareas` . `usuarios` (
                `id` INT(6) NOT NULL AUTO_INCREMENT,
                `username` VARCHAR(50) NOT NULL,
                `nombre` VARCHAR(50) NOT NULL,
                `apellidos` VARCHAR(100) NOT NULL,
                `contrasena` VARCHAR(100) NOT NULL,
                PRIMARY KEY (`id`)
                );";
        
            if ($conexion->query($sql))
            {
                return [true, "La tabla <b>'usuarios'</b> se creo correctamente."];
            }
            else
            {
                return [false, "No fue posible crear la tabla <b>'usuarios'</b>."];
            }
        }
        catch (mysqli_sql_exception $e)
        {
            return [false, $e->getMessage()];
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
     * 
     * @throws mysqli_sql_exception Captura excepciones relacionadas con MySQL.
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
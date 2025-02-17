<?php

    require_once "clases/tareas.php";
    require_once "clases/usuarios.php";

    /**
     *  TODO:
     *  - Podría eliminar la clave "success" y verificar si la función devuelve una conexión nula para comprobar que se realizó correctamente.
     * 
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
     * 
     * @throws mysqli_sql_exception Si ocurre un error en la consulta SQL.
     */
    function conectar_mysqli() : array {   
        try {
            // Obtener las credenciales de la base de datos desde las variables de entorno
            $host = getenv("MYSQL_HOST") ?: "db"; // "db" es el nombre del servicio en docker-compose
            $user = getenv("MYSQL_USER_WEB") ?: "root";
            $password = getenv("MYSQL_PASSWORD_WEB") ?: "test";
            $db = getenv("MYSQL_DATABASE_TAREA_UD4") ?: ""; // Puede que la base de dato no haya sido creada todavía. Si no está configurada se guarda una cadena vacía en la variable.           
            // Configurar mysqli para lanzar excepciones automáticamente y no tener que usar connect_errno manualmente. Evita errores silenciosos.
            // https://www.php.net/manual/en/mysqli-driver.report-mode.php
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);            
            // Crear la conexión sin seleccionar la base de datos por si no existe.
            $conexion_mysqli = new mysqli($host, $user, $password);
            // Asegurar una codificación adecuada de caracteres
            $conexion_mysqli->set_charset("utf8mb4");
            // Compruebo que la base de datos existe
            // ERROR: La consulta SHOW DATABASE no admite placeholders
            //$sql_check = "SHOW DATABASES LIKE ?;";
            //Obtener una lista de las bases de datos disponibles
            $sql_check = "SHOW DATABASES";
            $resultado = $conexion_mysqli->query($sql_check);
            
            // Convertir el resultado en un array de nombres de bases de datos
            $databases = [];
            while ($row = $resultado->fetch_array(MYSQLI_NUM)){
                $databases[] = $row[0];
            }
            // Verifico si mi base de datos existe en la lista
            if (in_array($db, $databases)) {
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
     * @return array Devuelve un array asociativo con la siguiente información: 
     *               - 'success' (bool): Indica si la base de datos se creó correctamente o ya existe.
     *               - 'mensaje' (string): Mensaje de éxito o error.
     * 
     * @throws mysqli_sql_exception Si ocurre un error en la consulta SQL.
     */ 
    function crear_base_datos (mysqli $conexion_mysqli) : array {
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
                return ["success" => true, "mensaje" => "La base de datos 'tareas' ya existe."];
            }
            

            $sql = "CREATE DATABASE IF NOT EXISTS `tareas` DEFAULT CHARACTER SET utf8mb4;";
            
            if ($conexion_mysqli->query($sql)) {
                
                // Si se creó la base de datos sin problemas, usarla.
                $conexion_mysqli->select_db('tareas');
                return ["success" => true, "mensaje" => "Base de datos 'tareas' creada correctamente."];
            }

        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "mensaje" => "Error al crear la base de dato: " . $e->getMessage()];
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
     * @return array Devuelve un array asociativo con la siguiente información:
     *               - 'success' (bool): true si la tabla se creo correctamente o ya existe, false en caso contrario.          
     *               - 'mensaje' (string): Mensaje de éxito o error.
     * 
     * @throws mysqli_sql_exception Si ocurre un error en la consulta SQL.
     */
    function crear_tabla_usuario (mysqli $conexion_mysqli) : array {
        try {
            //Verificar si la tabla ya existe
            $sql_check = "SHOW TABLES LIKE 'usuarios';";
            $resultado = $conexion_mysqli->query($sql_check);

            if ($resultado && $resultado->num_rows > 0) {
                return ["success" => true, "mensaje" => "La tabla 'usuarios' ya existe."];
            }

            $sql = "CREATE TABLE IF NOT EXISTS `tareas`.`usuarios` (
                `id` INT UNSIGNED AUTO_INCREMENT,
                `username` VARCHAR(50) NOT NULL,
                `nombre` VARCHAR(50) NOT NULL,
                `apellidos` VARCHAR(100) NOT NULL,
                `contrasena` VARCHAR(100) NOT NULL,
                `rol` TINYINT(1) NOT NULL,
                CONSTRAINT pk_usuarios PRIMARY KEY (`id`),
                CONSTRAINT uk_usuarios_username UNIQUE (`username`),
                CONSTRAINT chk_rol CHECK (`rol` IN (0, 1))
                ) ENGINE=InnoDB;";
        
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
     * @return array Devuelve un array asociativo con la siguiente información:
     *               - 'success' (bool): true si la tabla se creó correctamente o ya existe, false en caso contrario.
     *               - 'mensaje' (string): Mensaje de éxito o error.
     * 
     * @throws mysqli_sql_exception Si ocurre un error en la consulta SQL.
     */
    function crear_tabla_tareas (mysqli $conexion_mysqli) : array {
        try {   
            //Comprobar si la tabla 'tareas' ya existe
            $sql_check = "SHOW TABLES LIKE 'tareas';";
            $resultado = $conexion_mysqli->query($sql_check);

            if($resultado && $resultado->num_rows > 0) {
                return ["success" => true, "mensaje" => "La tabla 'tareas' ya existe"];
            }

            //Crear la tabla tareas y vincularla a la tabla usuarios mediante una clave foranea
            $sql = "CREATE TABLE IF NOT EXISTS `tareas`.`tareas` (
                `id` INT UNSIGNED AUTO_INCREMENT,
                `titulo` VARCHAR(50) NOT NULL,
                `descripcion` VARCHAR(250),
                `estado` ENUM('Pendiente', 'En proceso', 'Completada') NOT NULL,
                `id_usuario` INT UNSIGNED NOT NULL,
                CONSTRAINT pk_tareas PRIMARY KEY (`id`),
                CONSTRAINT fk_tareas_usuarios FOREIGN KEY (`id_usuario`) REFERENCES `tareas`.`usuarios`(`id`)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            ) ENGINE=InnoDB;";

            if ($conexion_mysqli->query($sql) === true) {
                return ["success" => true, "mensaje" => "La tabla 'tareas' se creo correctamente."];
            }

            return ["success" => false, "mensaje" => "No fue posible crear la tabla 'tareas'."];

        }
        catch(mysqli_sql_exception $e)
        {
            return ["success" => false, "mensaje" => $e->getMessage()];
        }
    }

    /**
     * Crea la tabla 'ficheros' si no existe en la base de datos.
     *
     * La tabla 'ficheros' almacena información sobre archivos vinculados a tareas.
     * Cada fichero pertenece a una tarea, estableciendo una relación de 1:N.
     *
     * @param mysqli $conexion_mysqli Conexión activa a la base de datos MySQL.
     * @return array Resultado de la operación con 'success' y 'mensaje'.
     */
    function crear_tabla_ficheros (mysqli $conexion_mysqli) : array {
        try {
            //Comprobar si la tabla 'tareas' ya existe
            $sql_check = "SHOW TABLES LIKE 'ficheros';";
            $resultado = $conexion_mysqli->query($sql_check);
            if($resultado && $resultado->num_rows > 0) {
                return ["success" => true, "mensaje" => "La tabla 'ficheros' ya existe"];
            }
            // Crear la tabla ficheros y vincularla a la tabla tareas. Cardinalidad 1:N
            $sql = "CREATE TABLE IF NOT EXISTS `tareas`.`ficheros` (
                `id` INT UNSIGNED AUTO_INCREMENT,
                `nombre` VARCHAR(100) NOT NULL COMMENT 'Nombre del fichero',
                `file` VARCHAR(250) NOT NULL COMMENT 'Ruta donde se aloja el fichero',
                `descripcion` VARCHAR(250) COMMENT 'Descripción del contenido del fichero',
                `id_tarea` INT UNSIGNED NOT NULL,
                CONSTRAINT pk_ficheros PRIMARY KEY (`id`),
                CONSTRAINT fk_ficheros_tareas FOREIGN KEY (`id_tarea`) REFERENCES `tareas`.`tareas`(`id`)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
                ) ENGINE=InnoDB";
            if ($conexion_mysqli->query($sql) === true) {
                return ["success" => true, "mensaje" => "La tabla 'ficheros' se creo correctamente."];
            }
            return ["success" => false, "mensaje" => "No fue posible crear la tabla 'ficheros'."];
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "mensaje" => $e->getMessage()];
        }
    }

    /**
     * Selecciona todas las tareas de la base de datos junto con el nombre de usuario del creador.
     *
     * @param mysqli $conexion_mysqli Conexión activa a la base de datos.
     * 
     * @return array Devuelve un array asociativo con la siguiente información
     *     - "success" (bool) Indica si la operación fue exitosa.
     *     - ?"datos" (array) Devuelve una lista de tareas de la clase Tareas en caso de éxito.
     *     - ?"mensaje" (string|) Mensaje de error en caso de fallo.
     *
     * @throws mysqli_sql_exception Si ocurre un error en la consulta SQL.
     */
    function seleccionar_tareas(mysqli $conexion_mysqli) {
        try {
            // Consulta SQL para seleccionar todas las tareas
            $sql = "SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, usuarios.username
            FROM tareas
            INNER JOIN usuarios
            ON tareas.id_usuario = usuarios.id;";

            $resultados = $conexion_mysqli->query($sql);

            // Verificar si la consulta devolvió resultados
            if (!$resultados || $resultados->num_rows === 0) {
                return ["success" => false, "mensaje" => "No se encontraron tareas en la base de datos."];
            }

            // Obtener los resultados como un array asociativo.
            $conjunto_tareas = $resultados->fetch_all(MYSQLI_ASSOC);
            // Decodificar caracteres especiales en los valores del array
            $conjunto_tareas = array_map(function($tareas){
                // Las funciones nativas de PHP pueden pasarse como una cadena de caracteres a la función array_map
                array_map("htmlspecialchars_decode", $tareas);
                // Crear objeto Usuarios
                $usuario = new Usuarios ($tareas["username"]);
                return new Tareas (
                    $tareas["id"],
                    $tareas["titulo"],
                    $tareas["descripcion"],
                    $tareas["estado"],
                    $usuario
                );
            }, $conjunto_tareas);

            return ["success" => true, "tareas" => $conjunto_tareas];
        } catch (mysqli_sql_exception $e) {
            // Capturar errores y retornar un mensaje claro
            return ["success" => false, "mensaje" => "Error al obtener las tareas: " . $e->getMessage()];
        }
    }

    /**
     * Agrega una nueva tarea a la base de datos.
     *
     * @param mysqli $conexion_mysqli Conexión activa a la base de datos.
     * @param Tareas $tarea           Tarea de la clase Tareas a añadir a la base de datos.
     *
     * @return array Retorna un array asociativo con la siguiente información:
     *      - 'success' (bool) : true si se agregó correctamente, false en caso de error.
     *      - 'mensaje' (string) : información sobre la ejecución de la función.
     *
     * @throws mysqli_sql_exception Si ocurre un error al ejecutar la consulta.
     */
    function agregar_tarea(mysqli $conexion_mysqli, Tareas $tarea) {
        try {           

            // Validar que el estado sea correcto.
            $estados_validos = ["Pendiente", "En proceso", "Completada"];   
            if (in_array($tarea->getEstado(), $estados_validos, true)) {

                //Preparar la consulta
                $stmt = $conexion_mysqli->prepare("INSERT INTO tareas (titulo, descripcion, estado, id_usuario) VALUES (?,?,?,?)");
                // Guardar las propiedades de la clase Tareas en variables
                $titulo = $tarea->getTitulo();
                $descripcion = $tarea->getDescripcion();
                $estado = $tarea->getEstado();
                $id_usuario = $tarea->getUsuario()->getId();
                $stmt->bind_param("sssi", $titulo, $descripcion, $estado, $id_usuario);
                $stmt->execute();  

                // Cerrar la consulta preparada para liberar recursos
                $stmt->close();

                return ["success" => true, "mensaje" => ("La tarea '$titulo' con estado '$estado' se agregó correctamente.")];
            } else {
                return ["success" => false, "mensaje" => "El estado recibido por parámetro no es correcto."];
            }
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "mensaje" => ("Error a la hora de agregar la tarea: " . $e->getMessage())];
        }
    }

    /**
     * Modifica una tarea existente en la base de datos.
     *
     * @param mysqli $conexion Conexión a la base de datos.
     * @param Tareas $tareas   Objeto de la clase Tareas.
     * 
     * @return array Retorna un array asociativo con la siguiente información:
     *      - "success" (bool): Indica si la operación fue exitosa.
     *      - "mensaje" (string): Mensaje sobre el resultado de la operación.
     * 
     * @throws mysqli_sql_exception Si ocurre un error al ejecutar la consulta
     */
    function modificar_tarea(mysqli $conexion_mysqli, Tareas $tarea) {
        try {   
            // Validar estado
            $estados_validos = ["Pendiente", "En proceso", "Completada"];
            if(!in_array($tarea->getEstado(), $estados_validos, true)){
                return ["success" => false, "mensaje" => "El estado de la tarea no es correcto."];
            }

            //Crear consulta preparada
            $sql = "UPDATE tareas
            SET titulo = ?, descripcion = ?, estado = ?, id_usuario = ?
            WHERE id = ?";
            $stmt = $conexion_mysqli->prepare($sql);
            // Guardar las propiedades del objeto Tareas en variables
            $titulo = $tarea->getTitulo();
            $descripcion = $tarea->getDescripcion();
            $estado = $tarea->getEstado();
            $id_tarea = $tarea->getId();
            $id_usuario = $tarea->getUsuario()->getId();
            $stmt->bind_param("sssii", $titulo, $descripcion, $estado, $id_usuario, $id_tarea);
            $stmt->execute();

            // Verificar si se modificó algún registro
            if ($stmt->affected_rows === 0) {
                return ["success" => false, "mensaje" => "No se realizaron cambios en la tarea."];
            }
            return ["success" => true, "mensaje" => ("La tarea '$titulo' con estado '$estado' se modificó correctamente.")];
            
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "mensaje " => ("Error a la hora de modificar la tarea: " . $e->getMessage())];
        } finally {

            // Cerrar consulta
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    /**
     * Obtiene una tarea específica por su ID.
     * 
     * @param mysqli $conexion_mysqli Conexión activa a la base de datos.
     * @param Tareas $tarea           Objeto de la clase Tareas.
     * @return array Retorna un array asociativo con la siguiente información:
     *      - "success" (bool): Resultado de la consulta.
     *      - "resultado" (string | array): Resultado de la consulta, si la consulta
     *      fue exitosa devuelve un objeto de la clase Tarea, en caso de que no,
     *      devuelve un mensaje de error.
     */
    function seleccionar_tarea_id(mysqli $conexion_mysqli, Tareas $tarea) {
        try {
            //Consulta sql para selecionar una tarea por su id
            $sql = "SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, tareas.id_usuario, usuarios.username
            FROM tareas
            INNER JOIN usuarios
            ON tareas.id_usuario = usuarios.id
            WHERE tareas.id = ?;";

            // Consulta preparada
            $stmt = $conexion_mysqli->prepare($sql);

            // Guardar las propiedades de la clase Tareas en una variable
            $id_tarea = $tarea->getId();

            // Vincular parámetro
            $stmt->bind_param("i", $id_tarea);
            $stmt->execute();

            // Obtener resultado
            $resultado = $stmt->get_result();

            // Verificar resultados
            if($resultado->num_rows === 0) {
                return ["success" => false, "resultado" => "No se encontró ninguna tarea con ID $id_tarea."];
            }
            
            // Covertir el resultado en un array asociativo
            $tarea_datos = $resultado->fetch_assoc();

            // Liberar memoria del resultado
            $resultado->free();

            // Decodificar la tarea
            $tarea_datos = array_map("htmlspecialchars_decode", $tarea_datos);

            // Guardar los datos obtenidos en la base de datos en la clase Tareas
            $tarea->setId($tarea_datos["id"]);
            $tarea->setTitulo($tarea_datos["titulo"]);
            $tarea->setDescripcion($tarea_datos["descripcion"]);
            $tarea->setEstado($tarea_datos["estado"]);
            // Crear instancia de la clase usuario
            $usuario = new Usuarios($tarea_datos["username"], null, null, null, null, $tarea_datos["id_usuario"]);
            $tarea->setUsuario($usuario);
            
            return ["success" => true, "resultado" => $tarea];
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "resultado" => "Error al obtener la tarea: " . $e->getMessage()];
        } finally {
            if(isset($stmt)) {
                $stmt->close();
            }
        }
    }

    /**
     * Seleciconar las tareas asociadas a un username.
     * 
     * @param mysqli $conexion_mysqli Conexión activa mysqli.
     * @param Tareas $tarea           Objeto de la clase Tareas.
     * @return array Devuelve un array asociativo con la siguiente información.
     *      -"success"  (bool):     Devuelve true en caso de que la operación tuviese éxito, false en caso contrario.
     *      -?"mensaje" (string):   Mensaje informativo con información sobre el error.
     *      -?"tareas"  (array):    Array con datos de tipo Tareas donde se guardan los datos obtenidos de la consulta.
     */ 
    function seleccionar_tarea_username (mysqli $conexion_mysqli, Tareas $tarea) {
        try {
            //Consulta sql para selecionar una tarea por su id
            $sql = "SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, usuarios.username
            FROM tareas
            INNER JOIN usuarios
            ON tareas.id_usuario = usuarios.id
            WHERE usuarios.username = ?;";

            //Consulta preparada
            $stmt = $conexion_mysqli->prepare($sql);

            // Guardar el valor de las propiedades del objeto $tarea en variables.
            $username = $tarea->getUsuario()->getUsername();
            //Vincular parámetro
            $stmt->bind_param("s", $username);
            $stmt->execute();

            //Obtener resultado
            $resultado = $stmt->get_result();

            //Verificar resultados
            if($resultado->num_rows === 0) {
                return ["success" => false, "mensaje" => "El usuario '$username' no tiene tareas asociadas."];
            }
            
            // Covertir el resultado en un array asociativo
            $conjunto_tareas = $resultado->fetch_all(MYSQLI_ASSOC);

            // Liberar memoria del resultado
            $resultado->free();

            // Decodificar la tarea y guardar los datos obtenidos de la consulta en el objeto de la clase Tareas.

            $conjunto_tareas = array_map(function ($tarea){
                array_map ("htmlspecialchars_decode", $tarea);
                return new Tareas(
                    $tarea["id"],
                    $tarea["titulo"],
                    $tarea["descripcion"],
                    $tarea["estado"],
                    new Usuarios($tarea["username"])
                );
            }, $conjunto_tareas);
            
            return ["success" => true, "tareas" => $conjunto_tareas];
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "mensaje" => "Error al obtener la tarea: " . $e->getMessage()];
        } finally {
            if(isset($stmt)) {
                $stmt->close();
            }
        }
    }
    
    /**
     * Elimina una tarea de la base de datos por su ID.
     *
     * @param mysqli $conexion_mysqli Conexión activa a la base de datos.
     * @param Tareas $tarea           Objeto de la clase Tareas.
     * @return array Retorna un array asociativo con la siguiente información:
     *      - "success" (bool): Exito en la eliminación de la tarea.
     *      - "mensaje" (string): Mensaje informativo sobre la eliminación de la tarea.
     */
    function eliminar_tarea (mysqli $conexion_mysqli, Tareas $tarea) {
        try {
            //Preparar la sentencia sql para eliminar la tarea
            $sql = "DELETE FROM tareas WHERE id = ?";
            $stmt = $conexion_mysqli->prepare($sql);
            
            $id_tarea = $tarea->getId();
            //Vincular parámetros
            $stmt->bind_param("i", $id_tarea);
    
            //Ejecutar la consulta
            $stmt->execute();
    
            // Verificar cuántas filas fueron afectadas
            if ($stmt->affected_rows === 0) {
                return ["success" => false, "mensaje" => "No se encontró ninguna tarea con ID $id_tarea para eliminar."];
            }
            return ["success" => true, "mensaje" => "La tarea con ID $id_tarea se eliminó correctamente."];
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "mensaje" => "Error al eliminar la tarea: " . $e->getMessage()];
        } finally {
            if (isset($stmt)){
                $stmt->close();
            }
        }
    }
?>
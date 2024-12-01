<?php
    /**
     * Establece una conexión con una base de datos MySQL utilizando mysqli y comprueba si ha habido algún error.
     * 
     * @param string $host Host donde se encuentra el servidor mysql
     * @param string $user nombre del usuario para conectarse al sevidor mysql
     * @param string $pass contraseña para entrar en el servidor mysql
     * @param string $db nombre de la base de datos con la que se quiere realizar la conexión
     * @return mysqli objeto mysqli que representa la conexión a la base de datos
     */
    function conectar_mysqli ($host = "db", $user = "root", $pass = "test", $db = "tareas")
    {
        //Crea conexión
        $conexion = new mysqli ($host, $user, $pass, $db);

        //Comprobar conexión
        if ($conexion->connect_errno)
        {
            die("Fallo en la conexión: " . $conexion->connect_error . "Con número: " . $conexion->connect_errno);
        }
        
        //Devuelve la conexión
        return $conexion;
    }
    
    //mysqli orientado a objetos

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
            $sqlCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'tareas'";
            $resultado_comprobacion = $conexion->query($sqlCheck);

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
        finally
        {
            cerrar_conexion($conexion);
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
            $sqlCheck = "SHOW TABLES LIKE 'usuarios';";
            $resultado = $conexion->query($sqlCheck);

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
        finally
        {
            cerrar_conexion($conexion);
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
            $sqlCheck = "SHOW TABLES LIKE 'tareas';";
            $resultado = $conexion->query($sqlCheck);

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
        finally
        {
            cerrar_conexion($conexion);
        }
    }

    //PDO

    /**
     * Establece una conexión con una base de datos MySQL utilizando PDO.
     *
     * Esta función intenta conectarse a una base de datos MySQL con los parámetros proporcionados.
     * Si la conexión tiene éxito, devuelve un array indicando el estado de la conexión.
     * Si ocurre un error, captura la excepción y devuelve un mensaje descriptivo.
     *
     * @param string $host      El nombre del host o dirección IP del servidor de la base de datos. Por defecto, "db".
     * @param string $db        El nombre de la base de datos a la que conectarse. Por defecto, "tareas".
     * @param string $username  El nombre de usuario para la conexión. Por defecto, "root".
     * @param string $pass      La contraseña para la conexión. Por defecto, "test".
     *
     * @return array [bool, string] Un array donde el primer elemento indica si la conexión fue exitosa 
     *                              (true para éxito, false para error) y el segundo es un mensaje descriptivo.
     *
     * @throws PDOException Si ocurre un error durante la conexión, se lanza una excepción.
     */
    function conectar_PDO ($host="db", $db="tareas", $username="root", $pass="test",)
    {
        //TODO: Verificar que las variables de configuración sean válidas
        $conexion = null;

        try
        {
            $conexion = new PDO("mysql:host=$host;dbname=$db", $username, $pass);

            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return [$conexion, "Se efectuó la conexión con la base de datos '$db'."];
        }
        catch (PDOException $e)
        {
            return [false, "Error a la hora de conectar con la base de datos: " . $e->getMessage()];
        }
        finally
        {
            if($conexion)
            {
                $conexion = null;
            }
        }
    }

    /**
     * Obtiene una lista de usuarios de la base de datos.
     *
     * @param PDO $conexion Una instancia de la conexión PDO.
     * @return array [bool, mixed] Retorna un array donde el primer elemento es un booleano que indica el éxito 
     *                              (true si se obtuvieron datos, false en caso de error) y el segundo elemento 
     *                              es el resultado de la consulta o un mensaje de error.
     */
    function seleccionar_usuarios(PDO $conexion)
    {
        try
        {   
            // Preparar y ejecutar la consulta SQL
            $stmt = $conexion->prepare("SELECT `id`, `username`, `nombre`, `apellidos` FROM `usuarios`;");
            $stmt->execute();

            // Obtener resultados
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar si hay datos
            if (empty($usuarios))
            {
                return [false, "No se encontraron usuarios en la base de datos."];
            }
            
            foreach($usuarios as $usuario)
            {
                foreach($usuario as $dato_usuario)
                {
                    $dato_usuario = htmlspecialchars_decode($dato_usuario);
                }
            }
            return [true, $usuarios];
        } catch (PDOException $e)
        {
            // Manejar la excepción
            return [false, "Error al obtener los usuarios: " . $e->getMessage()];
        }
    }

?>
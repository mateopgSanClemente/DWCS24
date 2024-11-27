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
    function conectar ($host = "db", $user = "root", $pass = "test", $db = "tienda")
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


    /**
     * Función para crear una nueva base de datos en caso de que esta no exista.
     */
    function crear_base_datos ($conexion)
    {
        try {
            //Comprobar que la base de datos no existe
            $sqlCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'tienda';";
            $resultado_comprobacion = $conexion->query($sqlCheck);

            if ($resultado_comprobacion && $resultado_comprobacion->num_rows > 0)
            {
                return [false, "La base de datos 'tienda' ya existe."];
            }

            $sql = "CREATE DATABASE IF NOT EXISTS `tienda`;";

            if ($conexion->query($sql))
            {
                return [true, "Base de datos 'tienda' creada correctamente."];
            }
            else
            {
                return [false, "No se pudo crear la base de datos 'tienda'."];
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
     * Función para crear la tabla 'clientes' en la base de datos 'tienda'.
     */
    function crear_tabla ($conexion)
    {
        try 
        {
            //Verificar si la tabla ya existe
            $sqlCheck = "SHOW TABLES LIKE 'clientes';";
            $resultado = $conexion->query($sqlCheck);

            if ($resultado && $resultado->num_rows > 0)
            {
                return [false, "La tabla 'clientes' ya existe."];
            }

            $sql = "CREATE TABLE IF NOT EXISTS `tienda` . `clientes` (
                `id` INT(6) NOT NULL AUTO_INCREMENT,
                `nombre` VARCHAR(50) NOT NULL,
                `apellido` VARCHAR(100) NOT NULL,
                `edad` INT(6) NOT NULL,
                `provincia` VARCHAR(50) NOT NULL,
                PRIMARY KEY (`id`)
                );";
        
            if ($conexion->query($sql))
            {
                return [true, "La tabla 'clientes' se creo correctamente."];
            }
            else
            {
                return [false, "No fue posible crear la tabla 'clientes'."];
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
     * Función para insertar un nuevo usuario en la tabla clientes
     * 
     */
    function insertar_cliente ($conexion, $nombre, $apellidos, $edad, $provincia)
    {
        try
        {
            $stmt = $conexion->prepare("INSERT INTO clientes (nombre, apellido, edad, provincia) VALUES (?,?,?,?)");
            $stmt->bind_param("ssis", $nombre, $apellidos, $edad, $provincia);

            $stmt->execute();

            return [true, "Usuario creado correctamente."];
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

    function listar_cliente ($conexion)
    {
        try
        {
            /**
             * Otra forma de hacerlo sería:
             * SELECT * FROM clientes;
             */
            $sql = "SELECT id, nombre, apellido, edad, provincia FROM clientes;";

            $resultados = $conexion->query($sql);

            return [true, $resultados->fetch_all(MYSQLI_ASSOC)];
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

    function eliminar_cliente ($conexion, $id)
    {   try
        {
            $sql = "DELETE FROM clientes WHERE id = $id;";
            $conexion->query($sql);
            return [true, "El cliente con id $id se eliminó correctamente."];
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
?>
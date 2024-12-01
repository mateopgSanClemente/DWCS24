<?php
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
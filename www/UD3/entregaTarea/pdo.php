<?php

    /**
     * Establece una conexión con una base de datos MySQL utilizando PDO.
     *
     * @param string $host     El nombre del host o dirección del servidor de la base de datos. Por defecto, "db".
     * @param string $db       El nombre de la base de datos a la que se desea conectar. Por defecto, "tareas".
     * @param string $username El nombre de usuario para la conexión. Por defecto, "root".
     * @param string $pass     La contraseña asociada al usuario. Por defecto, "test".
     *
     * @return array Un array donde el primer elemento es la conexión PDO (o `false` si falla) 
     *               y el segundo elemento es un mensaje indicando el estado de la conexión.
     *
     * @throws PDOException Si ocurre un error crítico en la conexión, este se captura y devuelve un mensaje de error.
     *
     * @example
     * list($conexion, $mensaje) = conectar_PDO();
     * if ($conexion) {
     *     echo "Conexión exitosa: $mensaje";
     * } else {
     *     echo "Fallo en la conexión: $mensaje";
     * }
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

    /**
     * Agrega un nuevo usuario a la base de datos.
     *
     * @param PDO    $conexion   Objeto PDO que representa la conexión a la base de datos.
     * @param string $username   Nombre de usuario único para el nuevo usuario.
     * @param string $nombre     Nombre del usuario.
     * @param string $apellidos  Apellidos del usuario.
     * @param string $contrasena Contraseña del usuario (debería ser previamente encriptada para mayor seguridad).
     *
     * @return array Un array donde el primer elemento es un booleano:
     *               - `true` si el usuario fue insertado correctamente.
     *               - `false` si ocurrió un error durante la inserción.
     *               El segundo elemento es un mensaje descriptivo del resultado.
     *
     * @throws PDOException Si ocurre un error crítico al ejecutar la consulta preparada, este es capturado y devuelto como parte del mensaje.
     *
     * @example
     * list($exito, $mensaje) = agregar_usuario($conexion, 'jdoe', 'John', 'Doe', 'password123');
     * if ($exito) {
     *     echo $mensaje; // "El usuario John Doe se insertó correctamente."
     * } else {
     *     echo $mensaje; // "Error a la hora de insertar usuario: [detalle del error]."
     * }
     */
    function agregar_usuario($conexion, $username, $nombre, $apellidos, $contrasena)
    {
        try
        {
            $stmt = $conexion->prepare("INSERT INTO usuarios (username, nombre, apellidos, contrasena) VALUES (:username, :nombre, :apellidos, :contrasena)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':contrasena', $contrasena);

            $stmt->execute();
            
            //Cerrar el cursor
            $stmt->closeCursor();

            return [true, ("El usuario " . $nombre . " " . $apellidos . " se insertó correctamente.")];
        }
        catch(PDOException $e)
        {
            return [false, "Error a la hora de insertar usuario: " . $e->getMessage()];
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
     * Recupera los datos de un usuario desde la base de datos utilizando su ID.
     *
     * Esta función busca un usuario de la tabla 'usuarios' mediante su identificador único (ID). Si el usuario existe, se devuelven sus datos (nombre, apellidos y username). Si no se encuentra, se devuelve un mensaje indicando que no se encontró el usuario.
     *
     * @param PDO    $conexion Conexión PDO a la base de datos.
     * @param int    $id       ID del usuario que se desea recuperar.
     *
     * @return array Un array con dos elementos:
     *               - El primer elemento es un booleano (`true` si el usuario fue encontrado, `false` en caso contrario).
     *               - El segundo elemento es un mensaje indicando el estado de la operación. Si el usuario existe, devuelve los datos del usuario; de lo contrario, indica que no se encontró el usuario.
     *
     * @throws PDOException Si ocurre un error al ejecutar la consulta SQL.
     *
     * @example
     * list($exito, $resultado) = seleccionar_usuario_id($conexion, 123);
     * if ($exito) {
     *     // Mostrar los datos del usuario
     *     echo "Usuario encontrado: " . $resultado['username'];
     * } else {
     *     // Mostrar el mensaje de error
     *     echo $resultado;
     * }
     */
    function seleccionar_usuario_id($conexion, $id)
    {
        try
        {
            // Preparar la consulta para seleccionar datos del usuario
            $stmt = $conexion->prepare("SELECT username, nombre, apellidos, contrasena FROM usuarios WHERE id = :id");
            
            // Establecer el modo de recuperación de datos (por defecto, fetch as array)
            $stmt->setFetchMode(PDO::FETCH_ASSOC);  // Mejor usar PDO::FETCH_ASSOC para obtener los resultados como un array asociativo.
            
            // Ejecutar la consulta
            $stmt->execute(['id' => $id]);
    
            // Recuperar la primera fila de resultados
            $usuario = $stmt->fetch();
    
            // Verificar si se encontró un usuario con ese ID
            if (!$usuario) {
                return [false, "No se encontró ningún usuario con id = " . $id];
            } else {
                // Si existe, devolver los datos del usuario
                //TODO: APLICAR htmlspecialchars_decode() ANTES DE DEVOLVER LA FUNCIÓN! Me habría ahorrado trabajo.
                return [true, $usuario];
            }
        }
        catch (PDOException $e)
        {
            // Si ocurre un error con la consulta, devolver el mensaje de error
            return [false, "Error al obtener los datos del usuario: " . $e->getMessage()];
        }
    }
    
    /**
     * Modifica los datos de un usuario en la base de datos.
     *
     * @param PDO $conexion Conexión activa a la base de datos mediante PDO.
     * @param int $id ID del usuario que se desea modificar.
     * @param string $username Nuevo valor para el campo 'username'.
     * @param string $nombre Nuevo valor para el campo 'nombre'.
     * @param string $apellidos Nuevo valor para el campo 'apellidos'.
     * @param string $contrasena Nuevo valor para el campo 'contrasena', encriptado si corresponde.
     *
     * @return array Un array con dos elementos:
     *               - boolean: `true` si la modificación fue exitosa, `false` en caso contrario.
     *               - string: Mensaje descriptivo del resultado de la operación.
     */
    function modificar_usuario ($conexion, $id, $username, $nombre, $apellidos, $contrasena)
    {
        try {
            // Crear la consulta preparada
            $sql = "UPDATE usuarios 
                    SET username = :username, 
                        nombre = :nombre, 
                        apellidos = :apellidos, 
                        contrasena = :contrasena 
                    WHERE id = :id";
    
            $stmt = $conexion->prepare($sql);
    
            // Vincular los parámetros
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Verificar cuántas filas fueron afectadas
            if ($stmt->rowCount() > 0) {
                return [true, "El usuario con ID $id se ha actualizado correctamente."];
            } else {
                return [false, "No se realizaron cambios en el usuario con ID $id."];
            }
        } catch (PDOException $e) {
            // Manejar errores de forma segura
            return [false, "Error al actualizar el usuario: " . $e->getMessage()];
        }
        finally
        {
            if ($conexion)
                $conexion = null;
        }
    }
?>
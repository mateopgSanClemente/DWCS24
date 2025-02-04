<?php

    /**
     * Establece una conexión con MySQL utilizando PDO.
     * 
     * Primero intenta conectarse solo al host de MySQL. Si se especifica una 
     * base de datos, verifica su existencia antes de conectarse a ella. Si la 
     * base de datos no existe, se mantiene la conexión solo con el host.
     * 
     * @return array Devuelve un array con:
     *     - "success" (bool): `true` si la conexión es exitosa, `false` en caso de error.
     *     - "conexion" (PDO|null): Instancia de `PDO` si la conexión es exitosa, `null` si falla.
     *     - "mensaje" (string): Mensaje descriptivo del estado de la conexión.
     */
    function conectar_PDO () : array {
        try {

            // Recoger variable de entorno
            $host = getenv("MYSQL_HOST") ?: "db";
            $username = getenv("MYSQL_USER_WEB") ?: "root";
            $password = getenv("MYSQL_PASSWORD_WEB") ?: "test";
            $db = getenv("MYSQL_DATABASE_TAREA_UD4") ?: "";

            // Crear instancia PDO conectandose solo al host
            $conexion_PDO = new PDO("mysql:host=$host", $username, $password);

            // Settear modo de error
            $conexion_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if(!empty($db)) {
                // Comprobar que la base de datos 'tareas' existe antes de conectarme a ella.
                $sql_check = "SHOW DATABASES LIKE :db";
                $stmt = $conexion_PDO->prepare($sql_check);
                $stmt->execute([":db" => $db]);

                // En caso de que la base de datos exista
                if($stmt->fetch()) {
                    $conexion_PDO->exec("USE $db");
                    return [
                        "success" => true,
                        "conexion" => $conexion_PDO,
                        "mensaje" => "Se efectuó la conexión con la base de datos '$db'."
                    ];
                }
            }
            return [
                "success" => true,
                "conexion" => $conexion_PDO,
                "mensaje" => "Se efectuó la conexión con el host '$host'."
            ];
        } catch (PDOException $e) {
            return [
                "success" => false,
                "conexion" => null ,
                "mensaje" => "Error a la hora de conectar con la base de datos: " . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene una lista de usuarios de la base de datos.
     *
     * @param PDO $conexion Instancia de PDO con la conexión a la base de datos.
     * @return array Retorna un array asociativo con la siguiente información:
     *     - success (bool) : true si la sentencia se ejecutó correctamente, false
     *     en caso contrario.
     *     - datos? (array) : Colección de usuarios resultado de la selección.
     *     - mensaje? (string) : Información sobre la ejecución de la sentencia.
 
     */
    function seleccionar_usuarios(PDO $conexion_PDO) : array {
        try {   
            // Preparar y ejecutar la consulta SQL
            $stmt = $conexion_PDO->prepare("SELECT `id`, `username`, `nombre`, `apellidos` FROM `usuarios`;");
            $stmt->execute();

            // Obtener resultados
            $conjunto_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar si hay datos
            if (empty($conjunto_usuarios)) {
                return ["success" => false, "mensaje" => "No se encontraron usuarios en la base de datos."];
            }
            
            $conjunto_usuarios = array_map(function ($usuario){
                return array_map("htmlspecialchars_decode", $usuario);
            }, $conjunto_usuarios);
            return ["success" => true, "datos" => $conjunto_usuarios];
        } catch (PDOException $e) {
            // Manejar la excepción
            return ["success" => false, "mensaje" => "Error al obtener los usuarios: " . $e->getMessage()];
        }
    }

    /**
     * Agrega un nuevo usuario a la base de datos.
     *
     * @param PDO    $conexion   Conexión PDO activa con la base de datos.
     * @param string $username   Nombre de usuario.
     * @param string $nombre     Nombre real del usuario.
     * @param string $apellidos  Apellidos del usuario.
     * @param string $contrasena Contraseña en texto plano (se encriptará antes de almacenarse).
     * 
     * @return array Devuelve un array con la siguiente información:
     *     - "success" (bool) : true si el usuario se agregó correctamente, false en caso contrario.
     *     - "mensaje" (string) : información sobre como transcurrió la sentencia SQL.
     *
     */
    function agregar_usuario(PDO $conexion_PDO, string $username, string $nombre, string $apellidos, string $contrasena) {
        try {

            // Encriptar contraseña
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conexion_PDO->prepare("INSERT INTO usuarios (username, nombre, apellidos, contrasena) VALUES (:username, :nombre, :apellidos, :contrasena)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':contrasena', $contrasena_hash);

            $stmt->execute();

            return ["success" => true, "mensaje" => ("El usuario " . $nombre . " " . $apellidos . " se insertó correctamente.")];
        }
        catch(PDOException $e) {
            return ["success" => false, "mensaje" => "Error a la hora de insertar usuario: " . $e->getMessage()];
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
     *               - El segundo elemento es un mem nsaje indicando el estado de la operación. Si el usuario existe, devuelve los datos del usuario; de lo contrario, indica que no se encontró el usuario.
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



    /**
     * Función para eliminar usuarios y sus tareas asociadas
     */
    function eliminar_usuario($conexion, $id)
    {
        try {
            // Verificar que el usuario existe
            $stmt = $conexion->prepare("SELECT username, nombre, apellidos, contrasena FROM usuarios WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);  // Usamos PDO::FETCH_ASSOC para obtener resultados como array asociativo.
            $stmt->execute(['id' => $id]);
    
            // Recuperar la información del usuario
            $usuario = $stmt->fetch();
            $stmt->closeCursor();
    
            // Verificar si se encontró el usuario
            if (!$usuario) {
                return [false, "No se encontró ningún usuario con id = " . $id];
            } else {
                // Eliminar las tareas asociadas al usuario antes de eliminar al usuario
                $sql_tareas = "DELETE FROM tareas WHERE id_usuario = :id";
                $stmt_tareas = $conexion->prepare($sql_tareas);
                $stmt_tareas->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt_tareas->execute();
                
                // Eliminar el usuario
                $sql_usuario = "DELETE FROM usuarios WHERE id = :id";
                $stmt_usuario = $conexion->prepare($sql_usuario);
                $stmt_usuario->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt_usuario->execute();
    
                return [true, "El usuario " . $usuario['nombre'] . " " . $usuario['apellidos'] . " y todas sus tareas se eliminaron correctamente."];
            }
        } catch (PDOException $e) {
            return [false, "Se produjo un error en la eliminación del usuario: " . $e->getMessage()];
        } finally {
            // Cerrar la conexión
            if ($conexion) {
                $conexion = null;
            }
        }
    }

    //Función para seleccionar las tareas en función de su usuario y su estado
    function seleccionar_tarea_username_estado($conexion, $id_usuario, $estado=null)
    {
        try
        {   
            if(isset($estado))
            {
                $stmt = $conexion->prepare("SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, usuarios.username
                FROM tareas
                INNER JOIN usuarios
                ON tareas.id_usuario = usuarios.id
                WHERE tareas.id_usuario = :id_usuario AND tareas.estado = :estado");
                //Seleccionar como deben ser retornados lo datos
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                //Vincular los parámetros
                $stmt->bindParam(":id_usuario", $id_usuario);
                $stmt->bindParam(":estado", $estado);
                //Ejecutar consulta
                $stmt->execute();
                //Recuperar resultado
                $tareas = $stmt->fetchAll();
                //Comprobar que se encontró alguna tarea
                if(!empty($tareas))
                {
                    return [true, $tareas];
                }
                else
                {
                    return [false, "No se encontraron tareas para el usuario y el estado especificado."];
                }
            }
            else
            {
                $stmt = $conexion->prepare("SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, usuarios.username
                FROM tareas
                INNER JOIN usuarios
                ON tareas.id_usuario = usuarios.id
                WHERE id_usuario = :id_usuario");
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $stmt->bindParam(":id_usuario", $id_usuario);
                $stmt->execute();
                $tareas = $stmt->fetchAll();
                if(!empty($tareas))
                {
                    return [true, $tareas];
                }
                else
                {
                    return [false, "No se encontraron tareas para el usuario con id '". $id_usuario . "'."];
                }
            }
        }
        catch (PDOException $e)
        {
            return [false, "Se produjo un error a la hora de seleccionar las tareas en base a su estado y su usuario: " . $e->getMessage()] ;
        }
    }
?>
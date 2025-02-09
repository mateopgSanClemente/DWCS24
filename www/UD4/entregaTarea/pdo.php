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
     *  TODO:
     *  - Incluir el campo rol. x
     *  - Actualizar documentación. x
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
            $stmt = $conexion_PDO->prepare("SELECT `id`, `username`, `nombre`, `apellidos`, `rol` FROM `usuarios`;");
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
     *  TODO:
     *  - Incluir el campo rol.
     *  - Actualizar documentación.
     *  - Especificar tipo de dato que se recoge por parámetro.
     *  - Cambiar mensaje de en caso de que la consulta se ejecute sin problemas para que muestre el username.
     * Agrega un nuevo usuario a la base de datos.
     *
     * @param PDO    $conexion   Conexión PDO activa con la base de datos.
     * @param string $username   Nombre de usuario.
     * @param string $nombre     Nombre real del usuario.
     * @param string $apellidos  Apellidos del usuario.
     * @param string $contrasena Contraseña en texto plano (se encriptará antes de almacenarse).
     * @param int    $rol        Rol de usuario representado como entero, 0 para usuario y 1 para administrador.
     * 
     * @return array Devuelve un array con la siguiente información:
     *     - "success" (bool) : true si el usuario se agregó correctamente, false en caso contrario o si el usuario ya existe en la base de datos.
     *     - "mensaje" (string) : información sobre como transcurrió la sentencia SQL.
     *
     */
    function agregar_usuario(PDO $conexion_PDO, string $username, string $nombre, string $apellidos, string $contrasena, int $rol) {
        try {
            // Comprobar que el usuario no existe, el username debe ser único
            $sql_check = "SELECT username FROM usuarios WHERE username = :username";
            // Preparar consulta
            $stmt = $conexion_PDO->prepare($sql_check);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            // Recuperar los resultado -> no sería necesario.
            // $resultado_check = $stmt->fetch(PDO::FETCH_ASSOC); Tampoco sería necesario especificar el modo, al fin y al cabo solo voy a contar si me devuelve alguna fila, no a recuperar los datos.
            // Comprobar si hubo algún resultado
            if ($stmt->rowCount() > 0){
                return ["success" => false, "mensaje" => "El usuario '$username' ya existe en la base de datos 'usuarios'."];
            }
            // Encriptar contraseña
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conexion_PDO->prepare("INSERT INTO usuarios (username, nombre, apellidos, contrasena, rol) VALUES (:username, :nombre, :apellidos, :contrasena, :rol)");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':contrasena', $contrasena_hash, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);

            $stmt->execute();

            return ["success" => true, "mensaje" => ("El usuario '$username' se insertó correctamente.")];
        }
        catch(PDOException $e) {
            return ["success" => false, "mensaje" => "Error a la hora de insertar usuario: " . $e->getMessage()];
        }  
    }

    /** 
     *  TODO: - Incluir el rol en la selección de campos de usuario.
     * Recupera los datos de un usuario mediante su ID.
     *
     * @param PDO $conexion Objeto PDO para la conexión con la base de datos.
     * @param int $id ID del usuario a buscar.
     * 
     * @return array Retorna un array asociativo con la siguiente información:
     *     - "success" (bool) : true si la operación tiene éxito, false en caso contrario.
     *     - "mensaje"? (string) : retorna un mensaje informativo si la operación no tuvo éxito.
     *     - "datos"? (array) : retorna un array con el conjunto de los datos del usuario
     *      decodificados mediante htmlspecialchars_decode().
     */
    function seleccionar_usuario_id(PDO $conexion, int $id) : array {
        try {
            // Preparar la consulta para seleccionar datos del usuario
            $stmt = $conexion->prepare("SELECT username, nombre, apellidos, contrasena, rol FROM usuarios WHERE id = :id");
            
            // Vincular el parámetro id y hacer que este sea tratado como un entero
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            // Ejecutar la consulta
            $stmt->execute();
            
            // Establecer el modo de recuperación de datos (por defecto, fetch as array)
            // Recuperar la primera fila de resultados
            $datos_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verificar si se encontró un usuario con ese ID
            if (!$datos_usuario) {
                return ["success" => false, "mensaje" => "No se encontró ningún usuario con ID " . $id];
            }

            // Si existe, devolver los datos del usuario
            $datos_usuario = array_map("htmlspecialchars_decode", $datos_usuario);
            return ["success" => true, "datos" => $datos_usuario];
        } catch (PDOException $e) {
            // Si ocurre un error con la consulta, devolver el mensaje de error
            return ["success" => false, "mensaje" => "Error al obtener los datos del usuario: " . $e->getMessage()];
        }
    }
    
/**
 *  TODO:
 *  - El problema es que todavía reconoce el espacio en blanco como válido, debería utilizar una expresión regular
 *  mediante las funciones del fichero utils.php para validar los parámetros.
 *  - Incluir el campo rol.
 * 
 * Actualiza los datos de un usuario en la base de datos.
 *
 * Si se pasa un valor no vacío para la contraseña, se actualiza la contraseña
 * (después de encriptarla); de lo contrario, se actualizan solo los demás campos.
 *
 * @param PDO         $conexion   Conexión PDO a la base de datos.
 * @param int         $id         ID del usuario a modificar.
 * @param string      $username   Nuevo username del usuario.
 * @param string      $nombre     Nuevo nombre del usuario.
 * @param string      $apellidos  Nuevos apellidos del usuario.
 * @param int         $rol        Nuevo rol del usuario.
 * @param string|null $contrasena (Opcional) Nueva contraseña del usuario. Si es null o está vacía, no se actualiza.
 *
 * @return array Retorna un array asociativo con:
 *               - "success" => true si la actualización se realizó, o false si no se hicieron cambios o ocurrió un error.
 *               - "mensaje" => Mensaje descriptivo del resultado.
 */
function modificar_usuario(PDO $conexion, int $id, string $username, string $nombre, string $apellidos, int $rol, ?string $contrasena = null): array {
    try {
        // Verificar si se proporcionó una contraseña para actualizarla
        if (!empty($contrasena)) {
            // Si se actualiza la contraseña, incluirla en la consulta
            $sql = "UPDATE usuarios 
                    SET username = :username, 
                        nombre = :nombre, 
                        apellidos = :apellidos, 
                        contrasena = :contrasena,
                        rol = :rol
                    WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            
            // Encriptar la contraseña
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt->bindParam(':contrasena', $contrasena_hash, PDO::PARAM_STR);
        } else {
            // Si no se proporciona contraseña, no actualizar el campo 'contrasena'
            $sql = "UPDATE usuarios 
                    SET username = :username, 
                        nombre = :nombre, 
                        apellidos = :apellidos,
                        rol = :rol
                    WHERE id = :id";
            $stmt = $conexion->prepare($sql);
        }
    
        // Vincular los parámetros comunes
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Verificar si alguna fila fue afectada
        if ($stmt->rowCount() > 0) {
            return ["success" => true, "mensaje" => "El usuario con ID $id se ha actualizado correctamente."];
        }
                
        return ["success" => false, "mensaje" => "No se realizaron cambios en el usuario con ID $id."];
            
    } catch (PDOException $e) {
        return ["success" => false, "mensaje" => "Error al actualizar el usuario: " . $e->getMessage()];
    }     
}

    /**
     * Elimina un usuario de la base de datos.
     *
     * Esta función elimina un usuario de la base de datos mediante su ID. 
     * La tabla 'tareas' tiene una relación con 'usuarios' definida con `ON DELETE CASCADE`,
     * las tareas asociadas se eliminarán automáticamente sin necesidad de una consulta adicional.
     *
     * @param PDO $conexion Objeto de conexión a la base de datos.
     * @param int $id ID del usuario a eliminar.
     * 
     * @return array Retorna un array asociativo con los siguiente valores:
     *     - "success" (bool) : true si el usuario se eliminó con éxito, false en caso contrario.
     *     - "mensaje" (string) : mensaje informativo con el resultado de la ejecución de la función.
     */
    function eliminar_usuario(PDO $conexion, int $id) : array {
        try {
            // Verificar que el usuario existe
            $stmt = $conexion->prepare("SELECT username, nombre, apellidos, contrasena FROM usuarios WHERE id = :id");

            // Enlazar los parametros
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            // Usamos PDO::FETCH_ASSOC para obtener resultados como array asociativo.
            $stmt->execute();
    
            // Recuperar la información del usuario
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
    
            // Verificar si se encontró el usuario
            if (!$usuario) {
                return ["success" => false, "mensaje" => "No se encontró ningún usuario con ID " . $id];
            }

            // Eliminar las tareas asociadas al usuario antes de eliminar al usuario
            // Ya no es necesario, modifiqué la creación de la tabla tareas para que se eliminen automaticamente con el usuario.
            /*
            $sql_tareas = "DELETE FROM tareas WHERE id_usuario = :id";
            $stmt_tareas = $conexion->prepare($sql_tareas);
            $stmt_tareas->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_tareas->execute();
            */

            // Eliminar el usuario
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ["success" => true, "mensaje" => "El usuario " . htmlspecialchars_decode($usuario['nombre']) . " " . htmlspecialchars_decode($usuario['apellidos']) . " y todas sus tareas se eliminaron correctamente."];
        
        } catch (PDOException $e) {

            return ["success" => false, "mensaje" => "Se produjo un error en la eliminación del usuario: " . $e->getMessage()];
        }
    }

    /**
     * Selecciona tareas de un usuario filtradas opcionalmente por estado.
     *
     * @param PDO         $conexion_PDO  Conexión PDO a la base de datos.
     * @param int         $id_usuario    ID del usuario cuyas tareas se desean seleccionar.
     * @param string|null $estado       (Opcional) Estado de las tareas a filtrar. Es `null` por defecto para no aplicar filtro.
     *
     * @return array Devuelve un array con la estructura:
     *               - 'success' (bool): true si se encontraron tareas, false en caso contrario o si ocurrió un error.
     *               - 'datos'? (array): Lista de tareas si `success` es `true`.
     *               - 'mensaje'? (string): Mensaje descriptivo en caso de error o si no se encontraron tareas.
     *
     */
     function tareas_usuario_estado (PDO $conexion_PDO, int $id_usuario, ?string $estado = null) : array {
        try {

            // Consulta dinámica
            $sql = "SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, usuarios.username
                    FROM tareas
                    INNER JOIN usuarios
                    ON tareas.id_usuario = usuarios.id
                    WHERE tareas.id_usuario = :id_usuario";

            // Agregar condición concatenandola a la sentencia anterior en caso de que el estado esté seleccionado
            if(!empty($estado)) {
                $sql .= " AND tareas.estado = :estado";
            }

            // Preparar la consulta
            $stmt = $conexion_PDO->prepare($sql);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);

            // Si se especifica el estado, se enlaza a la consulta preparada
            if(!empty($estado)) {
                $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
            }

            // Ejecutar consulta
            $stmt->execute();

            //Seleccionar como debe ser el tipo de array que guarda los datos
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            //Recuperar resultado
            $tareas = $stmt->fetchAll();

            //Comprobar que se encontró alguna tarea
            if(empty($tareas)) {

                // Generar mensaje en función de si se definió o no el estado
                $mensaje = empty($estado)
                    ? "No se encontraron tareas para el usuario con ID $id_usuario."
                    : "No se encontraron tareas para el usuario con ID $id_usuario y con estado '$estado'.";
                return ["success" => false, "mensaje" => $mensaje];
            }
            
            return ["success" => true, "datos" => $tareas];
            
        }
        catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Error al seleccionar las tareas: " . $e->getMessage()] ;
        }
    }

    /**
     * Selecciona un usuario de la tabla usuarios por su username,
     * retorna la contraseña en caso e que exista.
     * 
     * @param PDO    $conexion_PDO  Conexión PDO a la base de datos.
     * @param string $username      Nombre de usuario.
     * 
     * @return array Devuelve un array asociativo con la siguiente información:
     *               - "success" (bool): true si se encontró al usuario, false en caso contrario.
     *               - "mensaje"? (string): mensaje informativo en caso de error.
     *               - "datos"? (string): contraseña y rol del usuario en caso de que exista.
     */
    function seleccionar_usuario_pass_rol (PDO $conexion_PDO, string $username) : array {
        try {
            // Consulta
            $sql = "SELECT contrasena, rol FROM usuarios WHERE username = :username";
            // Preparar consulta
            $stmt = $conexion_PDO->prepare($sql);
            // Vincular parámetros
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            // Ejecutar consulta
            $stmt->execute();
            // Si se encontró un usuario con ese username, retornarlo
            if ($stmt->rowCount() > 0) {
                // Recoger el resultado
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                return ["success" => true, "datos" => $resultado];
            }
            return ["success" => false, "mensaje" => "El usuario '$username' no existe en la base de datos."];
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => $e->getMessage()];
        }
    }

    /**
     * Selecciona una tarea por su ID, incluyendo información del usuario asignado.
     *
     * Realiza una consulta en la base de datos para obtener el título, la descripción y el estado de la tarea,
     * junto con el nombre de usuario del propietario de la tarea. Se utiliza una consulta JOIN entre las tablas
     * `tareas` y `usuarios`.
     *
     * @param PDO $conexion_PDO Conexión PDO activa a la base de datos.
     * @param int $id_tarea     ID de la tarea a seleccionar.
     *
     * @return array Devuelve un array asociativo con la siguiente estructura:
     *               - "success" (bool): Indica si la operación fue exitosa.
     *               - "datos" (array): Contiene los datos de la tarea (titulo, descripcion, estado, username) en caso de éxito.
     *               - "mensaje" (string): Mensaje de error en caso de que no se encuentre la tarea.
     */
    function seleccionar_tarea_id_PDO (PDO $conexion_PDO, int $id_tarea) : array {
        // Comprobar que la tarea existe
        $sql = "SELECT `tareas`.`titulo`, `tareas`.`descripcion`, `tareas`.`estado`, `usuarios`.`username`
                FROM `tareas`
                INNER JOIN `usuarios`
                ON `tareas`.`id_usuario` = `usuarios`.`id`
                WHERE `tareas`.`id` = :id_tarea";
        $stmt = $conexion_PDO->prepare($sql);
        $stmt->bindParam(":id_tarea", $id_tarea, PDO::PARAM_INT);
        $stmt->execute();
        // Comprobar que la tarea existe
        if ($stmt->rowCount() > 0) {
            // Si existe se retornan los datos después de recoger el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return ["success" => true, "datos" => $resultado];
        } else {
            // Si no existe, se retorn un mensaje de error
            return ["success" => false, "mensaje" => "La tarea con ID $id_tarea no existe en la base de datos."];
        }
    }

    /**
     * Inserta un archivo en la tabla 'ficheros' de la base de datos.
     *
     * Dependiendo de si se proporciona una descripción (no nula) o no, la función construye 
     * la consulta SQL de forma condicional para incluir (o excluir) la columna 'descripcion'.
     *
     * La tabla 'ficheros' debe tener las siguientes columnas::
     * - nombre (VARCHAR)
     * - file (VARCHAR) – se utiliza el nombre 'file' entre backticks porque es una palabra reservada.
     * - descripcion (VARCHAR), que debe permitir valores nulos.
     *
     * @param PDO         $conexion_PDO Conexión PDO activa a la base de datos.
     * @param string      $nombre       El nombre del archivo.
     * @param string      $archivo      Representa la ruta del fichero.
     * @param string|null $descripcion  Descripción del archivo (opcional). Si es null, no se incluirá en la consulta.
     *
     * @return array Devuelve un array asociativo con dos claves:
     *               - 'success' => true si la inserción se realizó correctamente, false en caso de error.
     *               - 'mensaje' => Mensaje informativo o de error.
     */
    function insertar_archivo(PDO $conexion_PDO, string $nombre, string $archivo, int $id_tarea, ?string $descripcion = null) {
        try {
            if ($descripcion !== null) {
                $sql = "INSERT INTO ficheros (nombre, `file`, descripcion, id_tarea) 
                        VALUES (:nombre, :file, :descripcion, :id_tarea)";
            } else {
                $sql = "INSERT INTO ficheros (nombre, `file`) 
                        VALUES (:nombre, :file)";
            }
            
            $stmt = $conexion_PDO->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':file', $archivo, PDO::PARAM_STR);
            $stmt->bindParam('id_tarea', $id_tarea, PDO::PARAM_INT);
            if ($descripcion !== null) {
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            }
            $stmt->execute();
    
            return ["success" => true, "mensaje" => "Archivo insertado correctamente"];
        } catch(PDOException $e) {
            return ["success" => false, "mensaje" => "Error: " . $e->getMessage()];
        }
    }

    /**
     *  TODO:
     *  - Documentar.
     * Seleccionar información ficheros
     */
    function seleccionar_archivos (PDO $conexion_PDO) : array {
        try {
            $sql = "SELECT id, nombre, `file`, descripcion FROM ficheros";
            $stmt = $conexion_PDO->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($stmt->rowCount()== 0)  {
                return ["success" => false, "mensaje" => "No hay ficheros en la base de datos."];
            }
            return ["success" => true, "datos" => $resultado];
            
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => $e->getMessage()];
        }
    }

    /**
     * Eliminar un fichero según su id
     * 
     */
    function eliminar_fichero (PDO $conexion_PDO, int $id_fichero) {
        try {
            $sql = ("DELETE FROM ficheros WHERE id = :id");
            $stmt = $conexion_PDO->prepare($sql);            
            $stmt->bindParam(':id', $id_fichero, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Error: " . $e->getMessage()];
        }
    }

    /**
     * Selecciona una fila de la tabla ficheros por su id
     */
    function seleccionar_fichero_ruta (PDO $conexion_PDO, int $id_fichero) {
        try {
            $sql = ("SELECT `file` FROM ficheros WHERE id = :id");
            $stmt = $conexion_PDO->prepare($sql);            
            $stmt->bindParam(':id', $id_fichero, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch();
            return ["success" => true, "datos" => $resultado];
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Error: " . $e->getMessage()];
        }
    }

    /**
     * Seleccionar ficheros or su id_tarea
     */
    function seleccionar_fichero_tarea (PDO $conexion_PDO, int $id_tarea) {
        try {
            $sql = ("SELECT * FROM ficheros WHERE id_tarea = :id_tarea");
            $stmt = $conexion_PDO->prepare($sql);            
            $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ["success" => true, "datos" => $resultado];
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Error: " . $e->getMessage()];
        }
    }
?>
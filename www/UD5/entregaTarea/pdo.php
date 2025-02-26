<?php
    require_once "clases/usuarios.php";
    require_once "clases/tareas.php";
    require_once "clases/ficheros.php";
    require_once "clases/dataBaseException.php";
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
     * Comprueba un si existen datos en la tabla usuarios y retorna un
     * array de objetos de la clase Usuarios.
     *
     * @param PDO $conexion Instancia de PDO con la conexión a la base de datos.
     * @return array Retorna un array asociativo con la siguiente información:
     *     - success (bool) : true si la sentencia se ejecutó correctamente, false
     *     en caso contrario.
     *     - datos? (array) : Colección de objetos de la clase Usuarios resultado de la selección.
     *     - mensaje? (string) : Información sobre la ejecución de la sentencia.
     */
    function seleccionar_usuarios(PDO $conexion_PDO) : array {
        try {   
            // Preparar y ejecutar la consulta SQL
            $stmt = $conexion_PDO->prepare("SELECT `id`, `username`, `nombre`, `apellidos`, `contrasena`, `rol` FROM `usuarios`;");
            $stmt->execute();

            // Obtener resultados
            $conjunto_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar si hay datos
            if (empty($conjunto_usuarios)) {
                return ["success" => false, "mensaje" => "No se encontraron usuarios en la base de datos."];
            }
            
            // Decodificar los usuarios y convertir cada uno en un objeto de tipo Usuarios
            $conjunto_usuarios = array_map(function ($usuario){
                $usuarios_decodificados = array_map("htmlspecialchars_decode", $usuario);
                    return new Usuarios (            
                        $usuarios_decodificados["username"],
                        $usuarios_decodificados["nombre"],
                        $usuarios_decodificados["apellidos"],
                        $usuarios_decodificados["rol"],
                        $usuarios_decodificados["contrasena"],
                        $usuarios_decodificados["id"]
                    );
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
     * @param PDO       $conexion   Conexión PDO activa con la base de datos.
     * @param Usuarios  $usuario    Objetos de la clase Usuarios.
     * 
     * @return array Devuelve un array con la siguiente información:
     *     - "success" (bool) : true si el usuario se agregó correctamente, false en caso contrario o si el usuario ya existe en la base de datos.
     *     - "mensaje" (string) : información sobre como transcurrió la sentencia SQL.
     */
    function agregar_usuario(PDO $conexion_PDO, Usuarios $usuario) {
        try {
            // Comprobar que el usuario no existe, el username debe ser único
            $sql_check = "SELECT username FROM usuarios WHERE username = :username";
            // Preparar consulta
            $username = $usuario->getUsername();
            $stmt = $conexion_PDO->prepare($sql_check);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            // Recuperar los resultado -> no sería necesario.
            // $resultado_check = $stmt->fetch(PDO::FETCH_ASSOC); Tampoco sería necesario especificar el modo, al fin y al cabo solo voy a contar si me devuelve alguna fila, no a recuperar los datos.
            // Comprobar si hubo algún resultado
            if ($stmt->rowCount() > 0){
                return ["success" => false, "mensaje" => "El usuario '{$usuario->getUsername()}' ya existe en la tabla 'usuarios'."];
            }
            // Encriptar contraseña
            $contrasena_hash = password_hash($usuario->getContrasena(), PASSWORD_DEFAULT);
            
            // Recoger los valores del objeto en variables, el método bindParam solo acepta que se le pasen variables por referencia.    
            $nombre = $usuario->getNombre();
            $apellidos = $usuario->getApellidos();
            $rol = $usuario->getRol();
            $stmt = $conexion_PDO->prepare("INSERT INTO usuarios (username, nombre, apellidos, contrasena, rol) VALUES (:username, :nombre, :apellidos, :contrasena, :rol)");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':contrasena', $contrasena_hash, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);

            $stmt->execute();

            return ["success" => true, "mensaje" => "El usuario '{$usuario->getUsername()}' se insertó correctamente."];
        }
        catch(PDOException $e) {
            return ["success" => false, "mensaje" => "Error, no fue posible insertar el usuario: " . $e->getMessage()];
        }  
    }

    /**
     * Recupera los datos de un usuario mediante su ID, los decodifica mediante htmlspecialchars_decode
     * y retorna un objeto de la clase Usuarios creado con estos.
     *
     * @param PDO $conexion Objeto PDO para la conexión con la base de datos.
     * @param int $id ID del usuario a buscar.
     * 
     * @return array Retorna un array asociativo con la siguiente información:
     *     - "success" (bool) : true si la operación tiene éxito, false en caso contrario.
     *     - "mensaje"? (string) : retorna un mensaje informativo si la operación no tuvo éxito.
     *     - "usuario"? (Usuario) : retorna un objeto de la clase Usuarios.
     */
    function seleccionar_usuario_id(PDO $conexion, Usuarios $usuario) : array {
        try {
            // Preparar la consulta para seleccionar datos del usuario
            $stmt = $conexion->prepare("SELECT username, nombre, apellidos, contrasena, rol FROM usuarios WHERE id = :id");
            // Recoger los valores de las propiedades del objeto en variables
            $id = $usuario->getId();
            // Vincular el parámetro id y hacer que este sea tratado como un entero
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            // Ejecutar la consulta
            $stmt->execute();
            
            // Establecer el modo de recuperación de datos (por defecto, fetch as array)
            // Recuperar la primera fila de resultados
            $usuario_resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verificar si se encontró un usuario con ese ID
            if (!$usuario_resultado) {
                return ["success" => false, "mensaje" => "No se encontró ningún usuario con el ID " . $id];
            }

            // Si existe, devolver los datos del usuario
            $usuario_decodificado = array_map("htmlspecialchars_decode", $usuario_resultado);
            $usuario->setUsername($usuario_decodificado["username"]);
            $usuario->setNombre($usuario_decodificado["nombre"]);
            $usuario->setApellidos($usuario_decodificado["apellidos"]);
            $usuario->setRol($usuario_decodificado["rol"]);
            $usuario->setContrasena($usuario_decodificado["contrasena"]);
            return ["success" => true, "usuario" => $usuario];
        } catch (PDOException $e) {
            // Si ocurre un error con la consulta, devolver el mensaje de error
            return ["success" => false, "mensaje" => "Error al obtener los datos del usuario: " . $e->getMessage()];
        }
    }
    
    /**
     * Actualiza los datos de un usuario en la base de datos.
     *
     * Si se pasa un valor no vacío para la contraseña, se actualiza la contraseña
     * (después de encriptarla); de lo contrario, se actualizan solo los demás campos.
     *
     * @param PDO         $conexion   Conexión PDO a la base de datos.
     * @param Usuarios    $usuario    Objeto de la clase Usuarios.
     *
     * @return array Retorna un array asociativo con:
     *               - "success" => true si la actualización se realizó, o false si no se hicieron cambios o ocurrió un error.
     *               - "mensaje" => Mensaje descriptivo del resultado.
     */
    function modificar_usuario(PDO $conexion, Usuarios $usuario): array {
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
        
            // Vincular los parámetros comunes, guardar primero las propiedades del objeto Usuarios en variables.
            $id = $usuario->getId();
            $username = $usuario->getUsername();
            $nombre = $usuario->getNombre();
            $apellidos = $usuario->getApellidos();
            $rol = $usuario->getRol();
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        
            // Ejecutar la consulta
            $stmt->execute();
        
            // Verificar si alguna fila fue afectada
            if ($stmt->rowCount() > 0) {
                return ["success" => true, "mensaje" => "El usuario $username con ID $id se ha actualizado correctamente."];
            }
                    
            return ["success" => false, "mensaje" => "No se realizaron cambios en el usuario $username con ID $id."];
                
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
     * @param PDO       $conexion Objeto de conexión a la base de datos.
     * @param Usuarios  $usuario Objeto de la clase Usuarios.
     * 
     * @return array Retorna un array asociativo con los siguiente valores:
     *     - "success" (bool) : true si el usuario se eliminó con éxito, false en caso contrario.
     *     - "mensaje" (string) : mensaje informativo con el resultado de la ejecución de la función.
     */
    function eliminar_usuario(PDO $conexion, Usuarios $usuario): array {
        try {
            // Verificar que el usuario existe
            $stmt = $conexion->prepare("SELECT username FROM usuarios WHERE id = :id");

            // Enlazar los parametros, guardar primero los valores de las propiedades del objeto Usuarios en variables.
            $id = $usuario->getId();
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            // Usamos PDO::FETCH_ASSOC para obtener resultados como array asociativo.
            $stmt->execute();
    
            // Recuperar la información del usuario
            $usuario_eliminar = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
    
            // Verificar si se encontró el usuario
            if ($usuario_eliminar === false) {
                return ["success" => false, "mensaje" => "No se encontró ningún usuario con el ID " . $id];
            }

            // Eliminar las tareas asociadas al usuario antes de eliminar al usuario
            // Ya no es necesario, modifiqué la creación de la tabla tareas para que se eliminen automaticamente con el usuario.
            /*
            $sql_tareas = "DELETE FROM tareas WHERE id_usuario = :id";
            $stmt_tareas = $conexion->prepare($sql_tareas);
            $stmt_tareas->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_tareas->execute();
            */
            $usuario->setUsername($usuario_eliminar["username"]);
            // Eliminar el usuario
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ["success" => true, "mensaje" => "El usuario " . htmlspecialchars_decode($usuario->getUsername()) . " y todas sus tareas se eliminaron correctamente."];
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Se produjo un error en la eliminación del usuario: " . $e->getMessage()];
        }
    }

    /**
     * Selecciona tareas de un usuario filtradas opcionalmente por estado.
     *
     * @param PDO         $conexion_PDO  Conexión PDO a la base de datos.
     * @param Tareas      $tarea         Objeto de la clase Tareas.
     *
     * @return array Devuelve un array con la estructura:
     *               - 'success' (bool): true si se encontraron tareas, false en caso contrario o si ocurrió un error.
     *               - 'datos'? (array): Lista de objetos de la clase Tareas si `success` es `true`.
     *               - 'mensaje'? (string): Mensaje descriptivo en caso de error o si no se encontraron tareas.
     *
     */
     function tareas_usuario_estado (PDO $conexion_PDO, Tareas $tarea) : array {
        try {

            // Consulta dinámica
            $sql = "SELECT tareas.id, tareas.titulo, tareas.descripcion, tareas.estado, usuarios.username
                    FROM tareas
                    INNER JOIN usuarios
                    ON tareas.id_usuario = usuarios.id
                    WHERE tareas.id_usuario = :id_usuario";

            // Agregar condición concatenandola a la sentencia anterior en caso de que el estado esté seleccionado
            if(!empty($tarea->getEstado())) {
                $sql .= " AND tareas.estado = :estado";
            }

            // Preparar la consulta
            $stmt = $conexion_PDO->prepare($sql);
            // Guardar los valores de las propiedades del objeto Tareas en variables.
            $id_usuario = $tarea->getUsuario()->getId();
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);

            // Si se especifica el estado, se enlaza a la consulta preparada
            if(!empty($tarea->getEstado())) {
                $estado = $tarea->getEstado();
                $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
            }

            // Ejecutar consulta
            $stmt->execute();

            //Seleccionar como debe ser el tipo de array que guarda los datos
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            //Recuperar resultado
            $tareas_coleccion = $stmt->fetchAll();

            //Comprobar que se encontró alguna tarea
            if(empty($tareas_coleccion)) {

                // Generar mensaje en función de si se definió o no el estado
                $mensaje = empty($tarea->getEstado())
                    ? "No se encontraron tareas para el usuario con ID $id_usuario."
                    : "No se encontraron tareas para el usuario con ID $id_usuario y con estado '$estado'.";
                return ["success" => false, "mensaje" => $mensaje];
            }
            
            // Decodificar la información asociada a las tareas
            $tareas_coleccion = array_map(function($tarea){
                array_map("htmlspecialchars_decode", $tarea);
                return new Tareas (
                    $tarea["id"],
                    $tarea["titulo"],
                    $tarea["descripcion"],
                    $tarea["estado"],
                    new Usuarios ($tarea["username"])
                );
            }, $tareas_coleccion);
            return ["success" => true, "datos" => $tareas_coleccion];
            
        }
        catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Error al seleccionar las tareas: " . $e->getMessage()] ;
        }
    }

    /**
     * Selecciona un usuario de la tabla usuarios por su username,
     * retorna la contraseña y el rol en caso e que exista.
     * 
     * @param PDO      $conexion_PDO  Conexión PDO a la base de datos.
     * @param Usuarios $usuario       Objetao de la clase Usuario.
     * 
     * @return array Devuelve un array asociativo con la siguiente información:
     *               - "success" (bool): true si se encontró al usuario, false en caso contrario.
     *               - "mensaje"? (string): mensaje informativo en caso de error.
     *               - "usuario"? (string): objeto de la clase Usuarios.
     */
    function seleccionar_usuario_pass_rol (PDO $conexion_PDO, Usuarios $usuario) : array {
        try {
            // Consulta
            $sql = "SELECT contrasena, rol FROM usuarios WHERE username = :username";
            // Preparar consulta
            $stmt = $conexion_PDO->prepare($sql);
            // Vincular parámetrosm, guardar el valor de las propiedades del objeto Usuarios en una variable.
            $username = $usuario->getUsername();
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            // Ejecutar consulta
            $stmt->execute();
            // Si se encontró un usuario con ese username, retornarlo
            if ($stmt->rowCount() > 0) {
                // Recoger el resultado
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $usuario->setContrasena($resultado["contrasena"]);
                $usuario->setRol($resultado["rol"]);
                return ["success" => true, "usuario" => $usuario];
            }
            return ["success" => false, "mensaje" => "El usuario '{$usuario->getUsername()}' no existe en la base de datos."];
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
     * @param Tareas $tarea     Objeto de la clase Tarea.
     *
     * @return array Devuelve un array asociativo con la siguiente estructura:
     *               - "success" (bool): Indica si la operación fue exitosa.
     *               - "tarea"   (array): Contiene un objeto de la clase Tareas con datos de la tarea en la base de datos (titulo, descripcion, estado, username) en caso de éxito.
     *               - "mensaje" (string): Mensaje de error en caso de que no se encuentre la tarea.
     */
    function seleccionar_tarea_id_PDO (PDO $conexion_PDO, Tareas $tarea) : array {
        // Comprobar que la tarea existe
        $sql = "SELECT `tareas`.`titulo`, `tareas`.`descripcion`, `tareas`.`estado`, `usuarios`.`username`
                FROM `tareas`
                INNER JOIN `usuarios`
                ON `tareas`.`id_usuario` = `usuarios`.`id`
                WHERE `tareas`.`id` = :id_tarea";
        $stmt = $conexion_PDO->prepare($sql);
        $id_tarea = $tarea->getId();
        $stmt->bindParam(":id_tarea", $id_tarea, PDO::PARAM_INT);
        $stmt->execute();
        // Comprobar que la tarea existe
        if ($stmt->rowCount() > 0) {
            // Si existe se retornan los datos después de recoger el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            // Guardar los datos en el objeto Tareas
            $tarea->setTitulo($resultado["titulo"]);
            $tarea->setDescripcion($resultado["descripcion"]);
            $tarea->setEstado($resultado["estado"]);
            $usuario = new Usuarios ($resultado["username"]);
            $tarea->setUsuario($usuario);
            return ["success" => true, "tarea" => $tarea];
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
     * @param Ficheros    $fichero      Objeto de la clase Ficheros.
     *
     * @return array Devuelve un array asociativo con dos claves:
     *               - 'success' => true si la inserción se realizó correctamente, false en caso de error.
     *               - 'mensaje' => Mensaje informativo o de error.
     */
    function insertar_archivo(PDO $conexion_PDO, Ficheros $fichero) {
        try {
            if ($fichero->getDescripcion() !== null) {
                $sql = "INSERT INTO ficheros (nombre, `file`, descripcion, id_tarea) 
                        VALUES (:nombre, :file, :descripcion, :id_tarea)";
            } else {
                $sql = "INSERT INTO ficheros (nombre, `file`, id_tarea) 
                        VALUES (:nombre, :file)";
            }
            
            $stmt = $conexion_PDO->prepare($sql);
            // Guardar el valor de las propiedades del objeto Ficheros en variables
            $nombre = $fichero->getNombre();
            $archivo = $fichero->getFile();
            $id_tarea = $fichero->getTareas()->getId();
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':file', $archivo, PDO::PARAM_STR);
            $stmt->bindParam('id_tarea', $id_tarea, PDO::PARAM_INT);
            if ($fichero->getDescripcion() !== null) {
                $descripcion = $fichero->getDescripcion();
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            }
            $stmt->execute();
    
            return ["success" => true, "mensaje" => "Archivo insertado correctamente"];
        } catch(PDOException $e) {
            throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
        }
    }

    /**
     * Seleccion las columnas id, nombre, file y descripcion de todas las filas de la tabla ficheros
     * mediante una conexión PDO. La información se decodifica mediante htmlspecialchars_decode
     * antes de convertirla a un objeto de tipo Ficheros.
     * 
     * @param PDO $conexion_PDO Conexión PDO activa.
     * @return array retorna un array asociativo con la siguiente información:
     *      -'success'  (bool):   true si la operación fie exitosa, false en caso contrario.
     *      -'mensaje'? (string): mensaje informativo en caso de que la operación no fuese exitosa.
     *      -'datos'?   (array):  colección de objetos de tipo Ficheros.
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
            $resultado = array_map(function($fichero){
                array_map("htmlspecialchars_decode", $fichero);
                return new Ficheros (
                    $fichero["id"],
                    $fichero["nombre"],
                    $fichero["file"],
                    $fichero["descripcion"],
                    new Tareas ($fichero["id_tarea"])
                );
            }, $resultado);
            return ["success" => true, "datos" => $resultado];
            
        } catch (PDOException $e) {
            throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
        }
    }

    /**
     * Eliminar un fichero según su id.
     * 
     * @param PDO $conexion_PDO Conexión activa PDO.
     * @param Ficheros $fichero Objeto de la clase Ficheros con la propiedad id establecida.
     * 
     * @return array Devuelve un array asociativo con los siguiente valores:
     *      -'success' (bool): true si la operación fue exitosa, false en caso contrario.
     *      -
     * 
     */
    function eliminar_fichero (PDO $conexion_PDO, Ficheros $fichero) {
        try {
            $sql = ("DELETE FROM ficheros WHERE id = :id");
            $stmt = $conexion_PDO->prepare($sql);    
            $id_fichero = $fichero->getId();
            $stmt->bindParam(':id', $id_fichero, PDO::PARAM_INT);
            $stmt->execute();
            return ["success" => true, "mensaje" => "El fichero con id $id_fichero."];
        } catch (PDOException $e) {
            throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
        }
    }

    /**
     * Selecciona la fila de la columna 'file' que corresponda con el id en la tabla ficheros.
     * 
     * @param PDO $conexion_PDO: Conexión pdo activa.
     * @param Ficheros $fichero: Objeto de la clase Ficheros que guardará la información seleccionada de la tabla.
     * 
     * @return array: Array asociativo con la siguient información.
     *      -'success' (bool): true en caso de que la operación sea exitosa, false en caso contrario.
     *      -'datos'? (array): Colección de objetos de tipo Ficheros.
     *      -'mensaje'? (string): mensaje con información del error en caso de que la operación no se de correctamente.
     */
    function seleccionar_fichero_ruta (PDO $conexion_PDO, Ficheros $fichero) {
        try {
            $sql = ("SELECT `file` FROM ficheros WHERE id = :id");
            $stmt = $conexion_PDO->prepare($sql); 
            $id_fichero = $fichero->getId();           
            $stmt->bindParam(':id', $id_fichero, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch();
            // Decodificar los datos de las tablas y guardarlos en un objeto de tipo Fichers
            $resultado = array_map(function($ruta_fichero){
                $ruta_fichero = htmlspecialchars_decode($ruta_fichero);
                return new Ficheros(
                    null,
                    null,
                    $ruta_fichero,
                    null,
                    null
                );
            }, $resultado);
            return ["success" => true, "datos" => $resultado];
        } catch (PDOException $e) {
            throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
        }
    }

    /**
     * Selecciona todos los datos de las filas que se correspondan con el id de la tarea asociada.
     * Guarda tdos estos datos en una colección de datos de tipo Ficheros.
     * 
     * @param PDO $conexion_PDO Conexión PDO activa.
     * @param Ficheros $fichero Objeto de la clase Ficheros con el valor del id de la tarea asociada.
     * 
     * @return array Retorna un array asociativo con la siguiente información:
     *      -'success' (bool): resultado de la operación, true en caso de que no ocurran errores y false en caso contrario.
     *      -'datos'?  (array): en caso de que la operación se de sin errores,
     *      devuelve una colección de objetos de tipo Ficheros que guarda información de la tabla.
     *      -'mensaje' (string): en caso de que ocurra una excepción de tipo PDOException devuelve un mensaje informativo.
     *      
     */
    function seleccionar_fichero_tarea (PDO $conexion_PDO, Ficheros $fichero) {
        try {
            $sql = ("SELECT * FROM ficheros WHERE id_tarea = :id_tarea");
            $stmt = $conexion_PDO->prepare($sql); 
            $id_tarea = $fichero->getTareas()->getId();           
            $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Decodificar los resultados y guardarlos en objetos de tipo Ficheros
            $resultado = array_map(function($fichero){
                array_map("htmlspecialchars_decode", $fichero);
                return new Ficheros(
                    $fichero["id"],
                    $fichero["nombre"],
                    $fichero["file"],
                    $fichero["descripcion"],
                    new Tareas ($fichero["id_tarea"])
                );
            }, $resultado);
            return ["success" => true, "datos" => $resultado];
        } catch (PDOException $e) {
            throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
        }
    }

    /**
     * Seleccionar el id de usuario que corresponde al username.
     * 
     * @param PDO $conexion_PDO: Conexión PDO activa.
     * @param Usuarios $usuarios: Objeto de la clase Usuarios
     * 
     * @return array: array asociativo con la siguiente información:
     *      -"success" (bool): indica si la operación fue exitosa.
     *      -"datos"   (Usuarios): Objeto Usuario con la información sobre el mismo.
     */
    function seleccionar_id_username (PDO $conexion_PDO, Usuarios $usuario) : array {
        try {
            // Consulta
            $sql = "SELECT id FROM usuarios WHERE username = :username";
            // Preparar consulta
            $stmt = $conexion_PDO->prepare($sql);
            // Vincular parámetros
            $username = $usuario->getUsername();
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            // Ejecutar consulta
            $stmt->execute();
            // Si se encontró un usuario con ese username, retornarlo
            if ($stmt->rowCount() > 0) {
                // Recoger el resultado
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $usuario->setId($resultado["id"]);
                return ["success" => true, "datos" => $usuario];
            }
            return ["success" => false, "mensaje" => "El usuario '{$usuario->getUsername()}' no existe en la base de datos."];
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => $e->getMessage()];
        }
    }
?>
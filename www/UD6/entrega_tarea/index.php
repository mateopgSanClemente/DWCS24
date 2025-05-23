<?php

declare(strict_types=1);

require_once 'flight/Flight.php';
// require 'flight/autoload.php';

Flight::route('/', function () {
    echo 'hello world!';
});

// Variables de entorno
$host = $_ENV["DATABASE_HOST"];
$name = $_ENV["MYSQL_DATABASE"];
$user = $_ENV["MYSQL_USER"];
$pass = $_ENV["MYSQL_PASSWORD"];

// Registrar la base de datos
Flight::register("db", "PDO", array("mysql:host=$host;dbname=$name", $user, $pass));

// 1. AUTENTICACIÓN

// 1.1. Registro de usuario: recibe nombre, email, password (hashed) y devuelve un mensaje de éxito o un error en caso de fallo.
Flight::route("POST /register", function(){
    try{
        // Recuperar los valores que se pansan en formato JSON a través del body de la solicitud HTTP
        $nombre = Flight::request()->data->nombre;
        $email = Flight::request()->data->email;
        $pass = Flight::request()->data->password;

        // Mostrar mensaje en caso de que falten datos necesarios para el registro
        if (empty($nombre) || empty($email) || empty($pass)) {
            Flight::json(["error" => "Faltan datos obligatorios", 400]);
            return;
        }
        // Hashear password
        $pass = password_hash($pass, PASSWORD_DEFAULT);

        // Sentencia SQL
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :pass);";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":email", $email);
        $sentencia->bindParam(":pass", $pass);

        // Mostrar mensaje de éxito o error en caso de fallo
        if ($sentencia->execute()){
            Flight::json(["mensaje" => "Usuario registrado correctamente", 201]);
        } else {
            Flight::json(["error" => "No se pudo realizar el registro.", 500]);
        }
    } catch (PDOException $e) {
        Flight::json(["error" => "Error en la base de datos: $e"], 500);
    }
});

// 1.2. Login: recibe email y password, verifica las credenciales. Si son correctas, genera y devuelve un token simple. Si no, devuelve el error correspondiente.
/**
 * TODO: Guardar el usuario autenticado en Flight
 */
Flight::route("POST /login", function(){
    try {
        // Recuperar los datos del body de la petición http
        $email = Flight::request()->data->email;
        $pass = Flight::request()->data->password;

        if (empty($email) || empty($pass)) {
            Flight::json(["error" => "En necesario un email y una password.", 400]);
            return;
        }
        // Recupero la inforación de la base de datos para contrastarta con la introducida por el usuario
        $sql = "SELECT `password` FROM usuarios WHERE email=:email;";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":email", $email);

        if($sentencia->execute()){

            // Comprobar si algún email de la BD coincide, en caso de que coincida comprobar que la contraseña es la correcta
            if($sentencia->rowCount() > 0){

                // Verificar que la contraseña se corresponde con el hash
                $passHash = $sentencia->fetch(PDO::FETCH_ASSOC)["password"];
                if(password_verify($pass, $passHash)){

                    // Generar token
                    $token = bin2hex(random_bytes(32));

                    // Almacenar el token en la base de datos
                    $sql = "UPDATE usuarios SET token = :token WHERE email = :email;";
                    $sentencia = Flight::db()->prepare($sql);
                    $sentencia->bindParam(":token", $token);
                    $sentencia->bindParam(":email", $email);
                    $sentencia->execute();
                    Flight::json(["mensaje" => "Login correcto",
                    "token" => $token]);
                } else {
                    Flight::json(["mensaje" => "Password incorrecto.", 401]);
                }
            } else {
                Flight::json(["mensaje" => "El email no existe en la base de datos.", 404]);
            }
        } else {
            Flight::json(["error" => "No se pudo realizar el login.", 500]);
        }
    } catch (PDOException $e) {
        Flight::json(["error" => "Error en la base de datos: " .$e->getMessage()], 500);
    }
});

// 2. Agenda

// 2.1. Listar contactos (/contactos): devuelve todos los contactos del usuario autenticado. Además, opcionalmente, tendrá que devolver solo un contacto obtenido a partir de su ID, teniendo en cuenta que debe pertenecer al usuario autenticado.

Flight::route("GET /contactos(/@id_contacto)", function($id_contacto = null){
    try {
        // Recupero el valor del token
        $token = Flight::request()->getHeader("X-Token");
        $sql = "SELECT id FROM usuarios WHERE token = :token;";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":token", $token);

        if($sentencia->execute()) {
            // Comprobar que existe alguna coincidencia
            if($sentencia->rowCount() > 0) {

                // Comprobar que el contacto pertenece al usuario
                $id_usuario = $sentencia->fetch(PDO::FETCH_ASSOC)["id"];
                $sql = "SELECT * FROM contactos WHERE id = :id_contacto AND usuario_id = :id_usuario";
                $sentencia = Flight::db()->prepare($sql);
                $sentencia->bindParam(":id_contacto", $id_contacto);
                $sentencia->bindParam(":id_usuario", $id_usuario);
                $sentencia->execute();

                if ($sentencia->rowCount() == 0) {
                    Flight::json(["mensaje" => "No tienes permiso para ver este contacto."], 403);
                    exit;
                }
                // Si el usuario está autenticado, recuperar sus contactos o un contacto determinado si se le pasa un id a la ruta
                $id_usuario = $sentencia->fetch(PDO::FETCH_ASSOC)["id"];
                $sql = "SELECT * FROM contactos WHERE usuario_id = :id_usuario";

                // En caso de que se reciba también un id de contacto
                if(!empty($id_contacto)){
                    $sql .= " AND id = :id_contacto;";
                }
                $sentencia = Flight::db()->prepare($sql);
                $sentencia->bindParam(":id_usuario", $id_usuario);

                if(!empty($id_contacto)){
                    $sentencia->bindParam(":id_contacto", $id_contacto);
                }

                $sentencia->execute();
                
                // Mensaje de error en caso de que el contacto no exista
                if($sentencia->rowCount() == 0) {
                    Flight::json(["error" => "Contacto inexistente"], 404);
                    exit;
                }

                // Devolver los contactos
                $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                Flight::json(["contactos" => $resultado]);
            } else {
                Flight::json(["mensaje" => "El usuario no está autenticado.", 401]);
            }
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            Flight::json($resultado);
        } else {
            Flight::json(["error" => "No se pudo realizar la consulta.", 500]);
        }
    } catch (PDOException $e) {
        Flight::json(["error" => "Error en la base de datos: " .$e->getMessage()], 500);
    }
});

// 2.2. Añadir contacto (/contactos): recibe nombre, telefono, email, y lo guarda, devolviendo la confirmación.
Flight::route("POST /contactos", function(){
    try {
        // Recuperar la información del body de la petición HTTP
        $nombre = Flight::request()->data->nombre;
        $telefono = Flight::request()->data->telefono;
        $email = Flight::request()->data->email;

        // Recuperar el token
        $token = Flight::request()->getHeader("X-Token");

        // Buscar el usuario por token
        $sql = "SELECT id FROM usuarios WHERE token = :token";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":token", $token);
        $sentencia->execute();

        if ($sentencia->rowCount() == 0) {
            Flight::json(["mensaje" => "Usuario no autenticado."], 401);
            return;
        }

        $id_usuario = $sentencia->fetch(PDO::FETCH_ASSOC)["id"];

        // Sentencia SQL para insertar un nuevo contacto
        $sql = "INSERT INTO contactos (nombre, telefono, email, usuario_id) VALUES (:nombre, :telefono, :email, :id_usuario);";
        $sentencia = Flight::db()->prepare($sql);

        // Vincular parámetros
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":telefono", $telefono);
        $sentencia->bindParam(":email", $email);
        $sentencia->bindParam(":id_usuario", $id_usuario);

        if ($sentencia->execute()){
            Flight::json(["mensaje" => "Contacto guardado correctamente.", 201]);
        } else {
            Flight::json(["error" => "No se pudo realizar la consulta.", 500]);
        }
    } catch (PDOException $e) {
        Flight::json(["error" => "Error en la base de datos: " .$e->getMessage()], 500);
    }
});

// 2.3. Editar contacto (/contactos): permite modificar un contacto, asegurando que sea del usuario autenticado.
Flight::route("PUT /contactos/@id_contacto", function($id_contacto){
    try {
        // Recuperar la información del body de la petición HTTP
        $nombre = Flight::request()->data->nombre;
        $telefono = Flight::request()->data->telefono;
        $email = Flight::request()->data->email;

        // Recuperar el token
        $token = Flight::request()->getHeader("X-Token");

        // Buscar el usuario por token
        $sql = "SELECT id FROM usuarios WHERE token = :token";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":token", $token);
        $sentencia->execute();

        if ($sentencia->rowCount() == 0) {
            Flight::json(["mensaje" => "Usuario no autenticado."], 401);
            exit;
        }

        $id_usuario = $sentencia->fetch(PDO::FETCH_ASSOC)["id"];

        // Comprobar que el contacto pertenece al usuario
        $sql = "SELECT * FROM contactos WHERE id = :id_contacto AND usuario_id = :id_usuario";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":id_contacto", $id_contacto);
        $sentencia->bindParam(":id_usuario", $id_usuario);
        $sentencia->execute();

        if ($sentencia->rowCount() == 0) {
            Flight::json(["mensaje" => "No tienes permiso para actualizar este contacto."], 403);
            exit;
        }

        // Actualizar el contacto
        $sql = "UPDATE contactos SET nombre = :nombre, telefono = :telefono, email = :email WHERE id = :id_contacto AND usuario_id = :id_usuario";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":telefono", $telefono);
        $sentencia->bindParam(":email", $email);
        $sentencia->bindParam(":id_contacto", $id_contacto);
        $sentencia->bindParam(":id_usuario", $id_usuario);

        if ($sentencia->execute()) {
            // Mensaje de error en caso de que el contacto no exista
            if($sentencia->rowCount() == 0) {
                Flight::json(["error" => "Contacto inexistente"], 404);
                exit;
            }
            Flight::json(["mensaje" => "Contacto actualizado correctamente."], 200);
        } else {
            Flight::json(["error" => "No se pudo actualizar el contacto."], 500);
        }

    } catch (PDOException $e){
        Flight::json(["error" => "Error en la base de datos: " .$e->getMessage()], 500);
    }
});

// 2.4. Borrar contacto (/contactos): elimina un contacto a partir de su ID siempre que sea del usuario autenticado.
Flight::route("DELETE /contactos/@id_contacto", function($id_contacto){
    try{
        // Recuperar el token
        $token = Flight::request()->getHeader("X-Token");

        // Buscar el usuario por token
        $sql = "SELECT id FROM usuarios WHERE token = :token";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":token", $token);
        $sentencia->execute();

        if ($sentencia->rowCount() == 0) {
            Flight::json(["mensaje" => "Usuario no autenticado."], 401);
            exit;
        }

        $id_usuario = $sentencia->fetch(PDO::FETCH_ASSOC)["id"];

        // Comprobar que el contacto pertenece al usuario
        $sql = "SELECT * FROM contactos WHERE id = :id_contacto AND usuario_id = :id_usuario";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":id_contacto", $id_contacto);
        $sentencia->bindParam(":id_usuario", $id_usuario);
        $sentencia->execute();

        if ($sentencia->rowCount() == 0) {
            Flight::json(["mensaje" => "No tienes permiso para eliminar este contacto."], 403);
            exit;
        }

        // Eliminar contacto
        $sql = "DELETE FROM contactos WHERE id = :id_contacto AND usuario_id = :id_usuario;";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":id_contacto", $id_contacto);
        $sentencia->bindParam(":id_usuario", $id_usuario);
        if ($sentencia->execute()) {

            // Mensaje de error en caso de que el contacto no exista
            if($sentencia->rowCount() == 0) {
                Flight::json(["error" => "Contacto inexistente"], 404);
                exit;
            }

            Flight::json(["mensaje" => "Contacto borrado correctamente."], 200);
        } else {
            Flight::json(["error" => "No se pudo borrar el contacto."], 500);
        }
    } catch (PDOException $e) {
        Flight::json(["error" => "Error en la base de datos: " .$e->getMessage()], 500);
    }
});

Flight::start();
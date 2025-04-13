<?php

    require_once 'flight/core-master/flight/Flight.php';

    // Variables de entorno
    $host = $_ENV['DATABASE_HOST'];
    $name = $_ENV['DATABASE_TEST'];
    $user = $_ENV['DATABASE_USER'];
    $pass = $_ENV['DATABASE_PASSWORD'];

    // Comprobar que Flight funciona correctamente
    Flight::route("/", function(){
        echo "Hola mundo!";
    });

    // Registrar la base de datos
    Flight::register("db", "PDO", array("mysql:host=$host;dbname=$name", $user, $pass));

    // Devolver los datos de todos los clientes en formato JSON
    Flight::route("GET /clientes(/@id)", function ($id = null) {

        // En caso de que se pase un id de cliente
        if(!empty($id)){
            $sentencia = Flight::db()->prepare("SELECT * FROM clientes WHERE id = :id;");
            $sentencia->bindParam(":id", $id);
        } else {
            // En caso de que no se especifique un id de clientes
            $sentencia = Flight::db()->prepare("SELECT * FROM clientes;");
        }
        $sentencia->execute();
        $datosClientes = $sentencia->fetchAll();

        // Formatear los datos
        Flight::json($datosClientes);
    });

    Flight::route("POST /clientes", function() {
        $nombre = Flight::request()->data->nombre;
        $apellidos = Flight::request()->data->apellidos;
        $edad = Flight::request()->data->edad;
        $email = Flight::request()->data->email;
        $telefono = Flight::request()->data->telefono;

        // Sentencia SQL
        $sql = "INSERT INTO clientes (nombre, apellidos, edad, email, telefono) VALUES (:nombre, :apellidos, :edad, :email, :telefono);";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":apellidos", $apellidos);
        $sentencia->bindParam(":edad", $edad);
        $sentencia->bindParam(":email", $email);
        $sentencia->bindParam(":telefono", $telefono);

        $sentencia->execute();

        Flight::jsonp(["Cliente guardado correctamente."]);
    });

    Flight::route("DELETE /clientes(@id)", function($id){
        $id = Flight::request()->data->id;

        // Sentencia SQL
        $sql = "DELETE FROM clientes WHERE id=:id;";
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":id", $id);

        $sentencia->execute();

        $filaEliminada = $sentencia->fetch();
        // Formatear datos
        Flight::json($filaEliminada);
    });

    Flight::route("PUT /clientes", function(){
        // Recoger datos para actualizar el usuario
        $id = Flight::request()->data->id;
        $nombre = Flight::request()->data->nombre;
        $apellidos = Flight::request()->data->apellidos;
        $edad = Flight::request()->data->edad;
        $email = Flight::request()->data->email;
        $telefono = Flight::request()->data->telefono;

        // Sentencia SQL
        $sql = "UPDATE clientes SET nombre = :nombre, apellidos = :apellidos, edad = :edad, email = :email, telefono = :telefono WHERE id=:id;";

        // Preparar sentencia SQL
        $sentencia = Flight::db()->prepare($sql);
        $sentencia->bindParam(":id", $id);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":apellidos", $apellidos);
        $sentencia->bindParam(":edad", $edad);
        $sentencia->bindParam(":email", $email);
        $sentencia->bindParam(":telefono", $telefono);

        $sentencia->execute();

        Flight::jsonp(["Se actualizo el cliente con id $id"]);
    });

    Flight::start();
?>
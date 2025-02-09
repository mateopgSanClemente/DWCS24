<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
    // Redirigir a index en caso de que la persona que pretende acceder lo haga sin sen administrador
    if ($_SESSION["rol"] !== 1) {
        header("Location: index.php");
        exit;
    }

    require_once "utils.php";
    //Recoger los resultados en variables
    $username = $_POST["username"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $contrasena = $_POST["contrasena"];
    // Convertir el tipo de dato a entero
    $rol = intval($_POST["rol"]);
    //Comprobar errores
    $resultado_validar = validar_usuario($username, $nombre, $apellidos, $rol, $contrasena);
    //Comprobar los resultados, aunque pienso que sería más conveniente hacerlo en la página del propio formulario
    if (!$resultado_validar["success"]){
        // Guardo los errores en una variable de sesión para mostrarlos dinamicamente en el formulario.
        $_SESSION["errorVal"] = $resultado_validar["errores"];
        // Redirigir e finalizar el script.
        header ("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    } else {
        require_once "pdo.php";
        //Filtrar los resultados
        $username = test_input($username);
        $nombre = test_input($nombre);
        $apellidos = test_input($apellidos);
        $contrasena = test_input($contrasena);
        //Crear conexión con la base de datos
        $resultado_conexion_PDO = conectar_PDO();
        // Variable que guarda la instancia PDO
        $conexion_PDO = $resultado_conexion_PDO["conexion"];
        if(!$resultado_conexion_PDO["success"]) {
            $_SESSION["errorConPDO"] = $resultado_conexion_PDO["mensaje"];
        } else {
            // Insertar los datos en la tabla usuarios
            $resultado_agregar_usuario = agregar_usuario($conexion_PDO, $username, $nombre, $apellidos, $contrasena, $rol);
            // Comprobar que el usuario se agrego correctamente
            if(!$resultado_agregar_usuario["success"]) {
                $_SESSION["errorInsUser"] = $resultado_agregar_usuario["mensaje"];
                header ("Location: " . $_SERVER["HTTP_REFERER"]);
                exit;
            }
            else {
                $_SESSION["success"] = $resultado_agregar_usuario["mensaje"];
                header ("Location: " . $_SERVER["HTTP_REFERER"]);
                exit;
            }
            $conexion_PDO = null;
        }
    }
?>
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
    // Guardar ID usuario en una variable
    $id_usuario = $_GET['id'];
    // Recuperar los datos enviados a través del formulario
    $usuario_username_nuevo = $_POST["username"];
    $usuario_nombre_nuevo = $_POST["nombre"];
    $usuario_apellidos_nuevo = $_POST["apellidos"];
    // Comprobar que la contraseña está definida, en caso contraro devolver null
    $usuario_contrasena_nuevo = isset($_POST["contrasena"]) ? $_POST["contrasena"] : null;
    // Incluir el rol y convertirlo en entero.
    $usuario_rol_nuevo = intval($_POST["rol"]);
    // Validar los datos, si no son válidos, mostrar mensaje de error, todos son obligatorios menos la contraseña
    $resultado_validar_usuario = validar_modificar_usuario($usuario_username_nuevo, $usuario_nombre_nuevo, $usuario_apellidos_nuevo, $usuario_rol_nuevo, $usuario_contrasena_nuevo);
    //Comprobar los resultados, aunque pienso que sería más conveniente hacerlo en la página del propio formulario
    if (!$resultado_validar_usuario["success"]){
        // Crear una lista dinámica de mensajes con información sobre los errores asociados a un campo.
        $_SESSION["errorVal"] = $resultado_validar_usuario["errores"];
        header ("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    } else {
        // Si los resultados son válidos, sanearlos. No tengo que sanear el valor del rol, la función validar_modificar_usuario controla que su valor sea el correcto.
        $usuario_username_nuevo = test_input($usuario_username_nuevo);
        $usuario_nombre_nuevo = test_input($usuario_nombre_nuevo);
        $usuario_apellidos_nuevo = test_input($usuario_apellidos_nuevo);
        $usuario_contrasena_nuevo = test_input($usuario_contrasena_nuevo);
        // Crear una conexión PDO
        require_once "pdo.php";
        $resultado_conexión_PDO = conectar_PDO();
        // Comprobar la conexión
        if (!$resultado_conexión_PDO["success"]){
            $_SESSION["errorConPDO"] = $resultado_conexión_PDO["mensaje"];
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        } else {
            // Guardar conexión PDO en una variable
            $conexion_PDO = $resultado_conexión_PDO["conexion"];
            // Modificar usuario
            $resultado_modificar_usuario = modificar_usuario($conexion_PDO, $id_usuario, $usuario_username_nuevo, $usuario_nombre_nuevo, $usuario_apellidos_nuevo, $usuario_rol_nuevo, $usuario_contrasena_nuevo);
            //Mostrar mensaje con los resultados de la modificación
            if (!$resultado_modificar_usuario["success"]){
                $_SESSION["errorInsUser"] = $resultado_modificar_usuario["mensaje"];
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            } else {
                $_SESSION["success"] = $resultado_modificar_usuario["mensaje"];
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
            // Cerrar conexión
            $conexion_PDO = null;
        }
    }
?>
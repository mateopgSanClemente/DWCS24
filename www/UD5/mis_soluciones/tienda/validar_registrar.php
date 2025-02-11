<?php
    session_start();
    //Recoger valores tomados del formularios, sanearlos y asignarlos a una variable
    require_once "utils_validar.php";
    $nombre = test_input($_POST["nombre"]);
    $apellidos = test_input($_POST["apellidos"]);
    $edad = test_input($_POST["edad"]);
    $provincia = test_input($_POST["provincia"]);

    // Crear instancia del la clase Usuario
    require_once "Usuario.php";
    $cliente = new Usuario ($nombre, $apellidos, $edad, $provincia);
    
    // Utilizar el método validar de la clase Usuario
    $resultado_validar = $cliente->validar();
    
    // Comprobar resultado de la validación
    if(!$resultado_validar["success"]){
        $_SESSION["errorVal"] = $resultado_validar["resultado"];
        header("Location: registrar.php");
        exit;
    } else{
        require_once("utils_bases_datos.php");
        //Crear conexión
        $conexion = conectar();
        // Insertar nuevo cliente
        $resultado_insertar = insertar_cliente($conexion, $cliente);
        if(!$resultado_insertar["success"])
        {
            $_SESSION["error"] = $resultado_insertar["mensaje"];
        }
        else
        {
            $_SESSION["success"] = $resultado_insertar["mensaje"];
        }
        header("Location: registrar.php");
        exit;
    }
?>
<?php
    session_start();
    require_once("utils_validar.php");

    $id = $_GET['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $provincia = $_POST['provincia'];

    //Validar los datos
    $error = false;
    $mensaje_error = [];
    //Validar nombre
    if(!comprobar_campo($nombre))
    {
        $error = true;
        $mensaje_error[] = "El campo 'nombre' no puede estar vacio y debe contener por lo menos un caracter alfabético."; 
    }
    //Validar apellidos
    if(!comprobar_campo($apellido))
    {
        $error = true;
        $mensaje_error[] = "El campo 'apellidos' no puede estar vacio y debe contener por lo menos un caracter alfabético.";
    }
    //Validar edad
    if(!comprobar_campo($edad))
    {
        $error = true;
        $mensaje_error[] = "El campo 'edad' no puede estar vacío y debe ser un número que se encuentre entre los valores 18 y 130.";
    }
    //Validar provincia
    if(!comprobar_campo($provincia))
    {
        $error = true;
        $mensaje_error[] = "El campo 'provincia' no puede estar vacio y debe contener por lo menos un caracter alfabético.";
    }

    if ($error) {
        $_SESSION["error"] = $mensaje_error;
        header("Location: modificar.php?id=$id&nombre=$nombre&apellido=$apellido");
        exit;

    } else {

        require_once("utils_bases_datos.php");
        
        $conexion = conectar();
        $id = test_input($id);
        $nombre = test_input($nombre);
        $apellido = test_input($apellido);
        $edad = test_input($edad);
        $provincia = test_input($provincia);          
        $resultado = modificar_cliente($conexion, $id, $nombre, $apellido, $edad, $provincia);

        if ($resultado[0] === true)
        {
            $_SESSION["success"] = $resultado[1];
            header ("Location: listar.php");
        }
        else if ($resultado[0] === false)
        {
            $_SESSION["error"] = $resultado[1];
            header ("Location: listar.php");
        }
        cerrar_conexion($conexion);
    }
?>
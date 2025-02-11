<?php
    session_start();
    require_once("utils_validar.php");
    // Sanear los datos que provengan de formularios
    $id = intval(test_input($_GET['id']));
    $nombre = test_input($_POST['nombre']);
    $apellido = test_input($_POST['apellidos']);
    $edad = test_input($_POST['edad']);
    $provincia = test_input($_POST['provincia']);

    // Crear un objeto Usuario con los datos del fomrulario
    require_once "Usuario.php";
    $cliente = new Usuario($nombre, $apellido, $edad, $provincia);
    $cliente->setId($id);
    // Validar usuario mediante el método validar del propiedo objeto.
    $resultado_validar = $cliente->validar();

    // Comprobar que la validación fue correcta
    if(!$resultado_validar["success"]){
        $_SESSION["error"] = $resultado_validar["resultado"];
        header("Location: modificar.php?id=$id&nombre=$nombre&apellido=$apellido");
        exit;
    } else {

        // Crear conexión mysqli
        require_once("utils_bases_datos.php");      
        $conexion = conectar();
          
        $resultado = modificar_cliente($conexion, $cliente);

        if ($resultado["success"])
        {
            $_SESSION["success"] = $resultado["mensaje"];
            header ("Location: listar.php");
        }
        else
        {
            $_SESSION["error"] = $resultado["mensaje"];
            header ("Location: listar.php");
        }
        cerrar_conexion($conexion);
    }
?>
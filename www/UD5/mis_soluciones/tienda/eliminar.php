<?php
    session_start();
    require_once "utils_bases_datos.php";
    $conexion = conectar();
    $id_usuario = $_GET['id'];
    $resultado_eliminar = eliminar_cliente($conexion, $id_usuario);
    if ($resultado_eliminar[0])
    {
        $_SESSION["success"] = $resultado_eliminar[1];
    }
    else if (!$resultado_eliminar[0])
    {
        $_SESSION["error"] = $resultado_eliminar[1];        
    }
    header("Location: listar.php");
    exit;
?>
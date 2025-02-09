<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
        exit;
    }
    require_once "pdo.php";
    // Recoger formulario GET
    $id_tarea = $_GET["id"];
    $id_fichero = $_GET["id_fichero"];
    // Crear conexión
    $resultado_conexion_PDO = conectar_PDO();
    $conexion_PDO = $resultado_conexion_PDO["conexion"];
    // Eliminar fichero
    // Eliminar de la carpeta 'files': necesito la ruta.
    // La función unlink elimina un fichero cuando se le pasa la ruta y mientras tenga los permisos necesarios.
    $fichero = seleccionar_fichero_ruta($conexion_PDO, $id_fichero);
    $ruta_fichero = $fichero["datos"][0];
    unlink($ruta_fichero);
    // Eliminar de la base de datos
    eliminar_fichero($conexion_PDO, $id_fichero);
    $conexion_PDO = null;
    header("Location: tareas.php?id=" . $id_tarea . "&eliminar=true");
    exit;
?>
<?php
session_start();
// Redirige al usuario al formulario de login en caso de que la sesión no exista.
if (!isset($_SESSION["usuario"])){
    header("Location: login.php?error=sesion");
}

if (!empty($_GET["id_fichero"])){
    $id_fichero = $_GET["id_fichero"];
    $id_tarea = $_GET["id"];
    // Recoger la url del fichero desde la base de datos
    require_once "pdo.php";
    // Conectar mediante PDO
    $resultado_conexion_PDO = conectar_PDO();
    if ($resultado_conexion_PDO["success"]){
        $conexion_PDO = $resultado_conexion_PDO["conexion"];
        $resultado_seleccionar_ruta = seleccionar_fichero_ruta($conexion_PDO, $id_fichero);
        if ($resultado_seleccionar_ruta["success"]){
            // Compruebo que el fichero existe
            $ruta_fichero = $resultado_seleccionar_ruta["datos"][0];
            if (file_exists($ruta_fichero));
            // Configurar las cabeceras para forzar la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($ruta_fichero) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($ruta_fichero));
            flush(); // Vacía el búfer de salida del sistema
            readfile($ruta_fichero);
            header("Location: tarea.php?id=$id_tarea");
            exit;
        }
    }

}
?>
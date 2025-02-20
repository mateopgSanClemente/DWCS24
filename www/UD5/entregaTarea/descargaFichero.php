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
        // Objeto de la clase Ficheros
        $fichero = new Ficheros ($id_fichero, null, null, null, new Tareas($id_tarea));
        $resultado_seleccionar_ruta = seleccionar_fichero_ruta($conexion_PDO, $fichero);
        if ($resultado_seleccionar_ruta["success"]){
            // Compruebo que el fichero existe
            $ruta_fichero = $resultado_seleccionar_ruta["datos"][0]->getFile();
            if (file_exists($ruta_fichero)){
                // Guardar mensaje de éxito en una variable de sesion
                // $_SESSION["succ_descarga"] = "El fichero se descargó correctamente.";
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
            } else {
                $_SESSION["err_descarga"] = "El fichero no existe.";
                header ("Location: tarea.php?id=$id_tarea");
                exit;
            }
        } else {
            $_SESSION["err_descarga"] = $resultado_seleccionar_ruta["mensaje"];
            header ("Location: tarea.php?id=$id_tarea");
            exit;
        }
    } else {
        $_SESSION["err_descarga"] = "No se pudo realizar la conexíon con la base de datos.";
        header ("Location: tarea.php?id=$id_tarea");
        exit;
    }
}
?>
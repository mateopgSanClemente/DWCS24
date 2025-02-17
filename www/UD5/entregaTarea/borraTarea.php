<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
        exit;
    }

    require_once "mysqli.php";
    $resultado_conexion_mysqli = conectar_mysqli();
    // Comprobar conexión
    if (!$resultado_conexion_mysqli["success"]){
        // Guardar mensaje en variable de sesion
        $_SESSION["errorConMysqli"] = $resultado_conexion_mysqli["error"];
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    } else {
        // Guarda conexión en una variable
        $conexion_mysqli = $resultado_conexion_mysqli["conexion"];
        $id_tarea = $_GET['id'];
        // Eliminar tarea
        $tarea = new Tareas($id_tarea);
        $resultado_eliminar_tarea = eliminar_tarea($conexion_mysqli, $tarea);
        // Mostrar el resultado de eliminar la tarea
        if(!$resultado_eliminar_tarea["success"]){
            $_SESSION["errorDel"] = $resultado_eliminar_tarea["mensaje"];
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        } else {
            $_SESSION["success"] = $resultado_eliminar_tarea["mensaje"];
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        // Cerrar conexión
        cerrar_conexion($conexion_mysqli);
    }
?>
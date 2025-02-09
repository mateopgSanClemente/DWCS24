<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
                 
    require_once 'utils.php';
    //Recoger los resultados en variables
    $tarea_titulo = $_POST['titulo'];
    $tarea_descripcion = $_POST['descripcion'];
    $tarea_estado = $_POST['estado'];
    $tarea_id_usuario = $_POST['usuario'];

    //Comprobación de errores y validacion de resultads
    $resultado_validar = validar_tarea($tarea_titulo, $tarea_descripcion, $tarea_estado, $tarea_id_usuario);
    if (!$resultado_validar["success"]){
        // Guardar el resultado en una variable de sesión
        $_SESSION["errorVal"] = $resultado_validar["errores"];
        header ("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    } else {
        require_once("mysqli.php");
        //Sanear los resultados
        $tarea_id = $_GET['id'];
        $tarea_titulo = test_input($tarea_titulo);
        $tarea_descripcion = test_input($tarea_descripcion);
        $tarea_estado = test_input($tarea_estado);
        $tarea_id_usuario = test_input($tarea_id_usuario);
        //Insertar los resultados en la tabla tareas
        //Conexion
        $resultado_conexion_mysqli = conectar_mysqli();
        if (!$resultado_conexion_mysqli["success"]){
            // Guardar el mensaje en una variable de sesión
            $_SESSION["errorConMysqli"] = $resultado_conexion_mysqli["error"];
            header ("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        } else {
            $conexion_mysqli = $resultado_conexion_mysqli["conexion"];
            $resultado_modificar = modificar_tarea($conexion_mysqli, $tarea_id, $tarea_titulo, $tarea_descripcion, $tarea_estado, $tarea_id_usuario);
            if (!$resultado_modificar["success"]){
                // Guardar el mensaje en una variable de sesión
                $_SESSION["errorInsTask"] = $resultado_modificar["mensaje"];
                header ("Location: " . $_SERVER["HTTP_REFERER"]);
                exit;
            } else {
                $_SESSION["success"] = $resultado_modificar["mensaje"];
                header ("Location: " . $_SERVER["HTTP_REFERER"]);
            }
            cerrar_conexion($conexion_mysqli);
        }
    }
?>

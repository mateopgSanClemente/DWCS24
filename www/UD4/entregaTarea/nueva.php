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
    $tarea_estado = isset($_POST['estado']) ? $_POST["estado"] : "";
    // Convertir el valor del id a un tipo entero
    $tarea_id_usuario = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : "";
    //Comprobación de errores y validacion de resultads
    $resultado_validar = validar_tarea($tarea_titulo, $tarea_descripcion, $tarea_estado, $tarea_id_usuario);
    if (!$resultado_validar["success"]){
        // Guardar el resultado en una variable de sesión
        $_SESSION["errorVal"] = $resultado_validar["errores"];
        header ("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    } else {
        require_once "mysqli.php";
        //Sanear los resultados
        $tarea_titulo = test_input($tarea_titulo);
        $tarea_descripcion = test_input($tarea_descripcion);
        $tarea_estado = test_input($tarea_estado);
        $tarea_id_usuario = test_input($tarea_id_usuario);
        //Insertar los resultados en la tabla tareas
        //Conexion mysqli
        $resultado_conexion_mysqli = conectar_mysqli();
        
        // Comprobar que la conexión se realizó correctamente.
        if (!$resultado_conexion_mysqli["success"]){
            // Guardar el mensaje en una variable de sesión
            $_SESSION["errorConMysqli"] = $resultado_conexion_mysqli["error"];
            header ("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        } else {
            $mysqli_conn = $resultado_conexion_mysqli["conexion"];
            // Insertar datos
            $resultado_agregar_tarea = agregar_tarea($mysqli_conn, $tarea_titulo, $tarea_descripcion, $tarea_estado, $tarea_id_usuario);
            // Mostrar mensaje de error o éxito
            if (!$resultado_agregar_tarea["success"]){
                $_SESSION["errorInsTask"] = $resultado_agregar_tarea["mensaje"];
                header ("Location: " . $_SERVER["HTTP_REFERER"]);
            } else {
                $_SESSION["success"] = $resultado_agregar_tarea["mensaje"];
                header ("Location: " . $_SERVER["HTTP_REFERER"]);
            }
            cerrar_conexion($mysqli_conn);
        }
    }
?>
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
    if($resultado_conexion_PDO["success"]){
        $conexion_PDO = $resultado_conexion_PDO["conexion"];
        // Eliminar fichero
        // Eliminar de la carpeta 'files': necesito la ruta.
        $fichero = new Ficheros($id_fichero);

        // Capturar excepcion dataBaseException
        try {
            $resultado_seleccionar_fichero = seleccionar_fichero_ruta($conexion_PDO, $fichero);
        } catch (DataBaseException $e){
            $_SESSION["errCon"] = $e;
            header("Location: tarea.php?id=" . $id_tarea);
        }

        if($resultado_seleccionar_fichero["success"]){
            $ruta_fichero = $resultado_seleccionar_fichero["datos"][0]->getFile();
            // La función unlink elimina un fichero cuando se le pasa la ruta y mientras tenga los permisos necesarios.
            unlink($ruta_fichero);

            // Capturar excepcion dataBaseException
            try {
                // Eliminar de la base de datos
                $resultado_eliminar = eliminar_fichero($conexion_PDO, $fichero);
            } catch (DataBaseException $e) {
                $_SESSION["errCon"] = $e;
                header("Location: tarea.php?id=" . $id_tarea);
            }
            if($resultado_eliminar["success"]){
                $conexion_PDO = null;
                $_SESSION["succ_eliminar"] = "El fichero se eliminó correctamente.";
                header("Location: tarea.php?id=" . $id_tarea);
                exit;
            } else {
                $conexion_PDO = null;
                $_SESSION["err_eliminar"] = $resultado_eliminar["mensaje"];
                header("Location: tarea.php?id=" . $id_tarea);
                exit;
            }
        } else {
            $conexion_PDO = null;
            $_SESSION["err_eliminar"] = $fichero["mensaje"];
            header("Location: tarea.php?id=" . $id_tarea);
            exit;
        }
    } else {
        $_SESSION["err_eliminar"] = "No se pudo conectar con la base de datos.";
        header("Location: tarea.php?id=" . $id_tarea);
        exit;
    }
?>
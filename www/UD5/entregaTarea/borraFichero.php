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
            // Crear objetos FicherosDBimp
            require_once "clases/ficherosDBImp.php";
            $fihceroDB = new FicherosDBImp($conexion_PDO);
            $resultado_seleccionar_fichero = $fihceroDB->buscaFichero($fichero->getId());
            $ruta_fichero = $resultado_seleccionar_fichero->getFile();
            // La función unlink elimina un fichero cuando se le pasa la ruta y mientras tenga los permisos necesarios.
            unlink($ruta_fichero);
            // Eliminar de la base de datos
            $resultado_eliminar = $fihceroDB->borraFichero($id_fichero);
            $conexion_PDO = null;
            $_SESSION["succ_eliminar"] = "El fichero se eliminó correctamente.";
            header("Location: tarea.php?id=" . $id_tarea);
            exit;
        } catch (DataBaseException $e){
            $conexion_PDO = null;
            $_SESSION["errCon"] = $e;
            header("Location: tarea.php?id=" . $id_tarea);
        }   
    } else {
        $_SESSION["err_eliminar"] = "No se pudo conectar con la base de datos.";
        header("Location: tarea.php?id=" . $id_tarea);
        exit;
    }
?>
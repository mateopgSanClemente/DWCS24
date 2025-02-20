<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
        
    // Validar los campos mediante el método estático de la clase Ficheros
    include_once "clases/ficheros.php";
    require_once "utils.php";
    $fichero_nombre = test_input($_POST["nombre"]);
    if (isset($_POST["descripcion"])){
        $fichero_descripcion = test_input($_POST["descripcion"]);
    }
    $fichero = $_FILES["fichero"] ?: null;
    $validar_fichero = Ficheros::validarCampos($fichero_nombre, $fichero_descripcion, $fichero);
    if($validar_fichero !== true){
        // Recoger los errores y mostrarlos en la página de formulario de fichero: subidaFichForm.php
        // Guardar los errores en una variable de sersión
        $_SESSION["err_fich_form"] = $validar_fichero;
        header ("Location: {$_SERVER["HTTP_REFERER"]}");
        exit;
    } else {

        // Carpeta para guardar los ficheros
        $target_dir = "files/";

        // Generar un nombre aleatorio para el fichero.
        $nombre_aleatorio = bin2hex(random_bytes(8));

        // Ruta completa del fichero a guardar
        $target_file = $target_dir . basename($fichero["name"]);
        $img_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cambio el valor de target_file para que su nombre corresponda con el nuevo nombre
        $target_file = $target_dir . $nombre_aleatorio .".". $img_file_type;

        // Subir la imagen a la carpeta de destino
        /**
         *  TODO:
         *  - Enviar mensaje de error en caso de que la función move_upload_file no funcione.
         * 
         */ 
        if(move_uploaded_file($fichero["tmp_name"], $target_file)){
            // Insertar la información en la base de datos
            // Conectar a la base de datos mediante PDO
            require_once "pdo.php";
            $resultado_conexion_PDO = conectar_PDO();
            if ($resultado_conexion_PDO["success"]){
                // ID tarea
                $id_tarea = (int)($_GET["id"]);
                $conexion_PDO = $resultado_conexion_PDO["conexion"];
                // Instancia de la clase Ficheros
                $fichero = new Ficheros(null, $fichero_nombre, $target_file, $fichero_descripcion, new Tareas($id_tarea));
                $resultado_producto = insertar_archivo($conexion_PDO, $fichero);
                // Cerrar conexión
                $conexion_PDO = null;
                // Redirigir
                $_SESSION["succ_upload"] = "Fichero subido correctamente.";
                header ("Location: tarea.php?id=" . $_GET["id"]);
                exit;
            } else {
                $_SESSION["err_upload"] = "No se pudo conectar con la base de datos.";
                header ("Location: tarea.php?id=" . $_GET["id"]);
                exit;
            }
        } else {
            $_SESSION["err_upload"] = "No se pudo mover el fichero a la carpeta de destino.";
            header ("Location: tarea.php?id=" . $_GET["id"]);
            exit;
        }
    }
?>
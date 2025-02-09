<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
        
    // Comprobar que la imagen se subió correctamente al formulario
    $fichero = $_FILES["producto"];
    if(isset($fichero) && $fichero["error"] == UPLOAD_ERR_OK){
        // Datos formualrio
        $fichero_nombre = $_POST["nombre"];
        if (isset($_POST["descripcion"])){
            $fichero_descripcion = $_POST["descripcion"];
        }
        $target_dir = "files/";
        /**
         *  TODO:
         *  - Arreglar esto, funciona para salir del paso me es bastante chapucero.
         *  - Podría simplificar todo el apartado de mensajes de error mediante arrays?
         *  - Me gustaría evitar que el código quedase tan anidado.
         */
        // Generar un nombre aleatorio para el fichero.
        $nombre_aleatorio = bin2hex(random_bytes(8));
        $target_file = $target_dir . basename($fichero["name"]);
        $img_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Cambio el valor de terget_file para que su nombre corresponda con el nuevo nombre
        $target_file = $target_dir . $nombre_aleatorio .".". $img_file_type;
        $tipos_validos = ["png", "jpg", "pdf"];

        // Verificar el que el tamaño del fichero es el correcto: 20Mb
        if($fichero["size"] <= 20000000){            
        // Verificar que el tipo de archivo es el correcto
            if(in_array($img_file_type, $tipos_validos)){
                // Recoger inputs y sanearlos
                require_once "utils.php";
                $fichero_nombre = test_input($_POST["nombre"]);
                if (isset($_POST["descripcion"])){
                    $fichero_descripcion = test_input($_POST["descripcion"]);
                }
                // Subir la imagen a la carpeta de destino
                if(move_uploaded_file($fichero["tmp_name"], $target_file)){
                    // Insertar la informació en la base de datos
                    // Conectar a la base de datos mediante PDO
                    require_once "pdo.php";
                    $resultado_conexion_PDO = conectar_PDO();
                    if ($resultado_conexion_PDO["success"]){
                        // ID tarea
                        $id_tarea = intval($_GET["id"]);
                        $conexion_PDO = $resultado_conexion_PDO["conexion"];
                        $resultado_producto = insertar_archivo($conexion_PDO, $fichero_nombre, $target_file, $id_tarea, $fichero_descripcion);
                        // Cerrar conexión
                        $conexion_PDO = null;
                        // Redirigir
                        header ("Location: tarea.php?id=" . $_GET["id"] . "&success=true");
                        exit;
                    }
                }
                // Mostrar mensaje de error si no se pudo subir el fichero
                header ("Location: tarea.php?erroUpload=true");
                exit;
            }
             // Mostrar mensaje de error si el fichero no es del tipo válido
            header ("Location: tarea.php?erroType=true");
            exit;
        }
        // Mostrar mensaje de error si el fichero supera los 20Mb
        header ("Location: tarea.php?erroSize=true");
        exit;
    }
    // Mostrar mensaje de error
    header ("Location: tarea.php?error=true");
    exit;
?>
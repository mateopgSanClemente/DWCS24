<?php
    session_start();
    // Verificar que el formulario se envió mediante el método POST
    if($_SERVER["REQUEST_METHOD"] == "POST") {

        // Comprobar que la imagen se subió correctamente al formulario
        if(isset($_FILES["foto_producto"]) && $_FILES["foto_producto"]["error"] == UPLOAD_ERR_OK){

            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["foto_producto"]["name"]);
            $img_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $tipos_validos = ["png", "jpg", "jpeg", "gif"];

            // Guardar valor de la foto en una variable
            $foto_producto = file_get_contents($_FILES["foto_producto"]["tmp_name"]);

            // Verificar el que el tamaño del fichero es el correcto
            if($_FILES["foto_producto"]["size"] <= 5000000){
                
                // Verificar que el tipo de archivo es el correcto
                if(in_array($img_file_type, $tipos_validos)){

                    // Recoger el resultado de los inputs del formulario y sanear su contenido
                    require_once "utils_validar.php";
                    $nombre_producto = test_input($_POST["nombre_producto"]);
                    $descripcion_producto = test_input($_POST["descripcion_producto"]);
                    $precio_producto = test_input(floatval($_POST["precio_producto"]));
                    $unidades_producto = test_input(intval($_POST["unidades_producto"]));

                    // Subir la imagen a la carpeta de destino
                    if(move_uploaded_file($_FILES["foto_producto"]["tmp_name"], $target_file)){

                        // Insertar la información en la base de datos
                        require_once "utils_bases_datos.php";

                        // Conexion
                        $con_mysqli = conectar();
                        list($verificar_insertar, $resultado_insertar) = insertar_producto($con_mysqli, $nombre_producto, $descripcion_producto, $precio_producto, $unidades_producto, $foto_producto);

                        // Verificar que la inserción se hizo correctamente
                        if ($verificar_insertar){
                            $_SESSION["success"] = $resultado_insertar;
                            header("Location: introducir_producto.php");
                            // Recupero la foto del último producto añadido desde mostrar_foto.php
                        } else {
                            $_SESSION["error"] = $resultado_insertar;
                            header("Location: introducir_producto.php");
                        }

                        cerrar_conexion($con_mysqli);
                    } else {
                        $_SESSION["error"] = "La imagen no se pudo subir correctamente";
                        header("Location: introducir_producto.php");
                    }
                } else {
                    $_SESSION["error"] = "La imagen no es del tipo correcto. Solo se admiten imágenes de tipo 'jpg', 'jpeg', 'png' o 'gif'";
                    header("Location: introducir_producto.php");
                }
            } else {
                $_SESSION["error"] = "El tamaño del fichero supera los 5Mb";
                header("Location: introducir_producto.php");
            }
        } else {
            $_SESSION["error"] = "La foto no se subió correctamente al formulario.";
            header("Location: introducir_producto.php");
        }
    } else {
        $_SESSION["error"] = "El método usado para eniviar el formulario no es el método POST.";
        header("Location: introducir_producto.php");
    }
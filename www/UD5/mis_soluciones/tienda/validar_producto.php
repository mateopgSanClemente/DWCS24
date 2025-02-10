<!DOCTYPE html>
<html lang="es">
<?php include_once "head.php";?>
<body>
    <?php include_once "header.php";?>
    <div class="content-fluid">
        <div class="row">
            <?php include_once "nav_menu.php";?>
            <main class="col-md-9 main-content">
                <h2 class="mb-4">Resultado insertar producto</h2>
                <?php
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
                                            echo "<div class='alert alert-success' role='alert'>" . $resultado_insertar . "</div>";
                                            
                                            // Recupero la foto del último producto añadido
                                            echo "<img src='mostrar_foto.php' class='img-fluid rounded shadow mx-auto d-block'>";
                                        } else {
                                            echo "<div class='alert alert-warning' role='alert'>" . $resultado_insertar . "</div>";
                                        }

                                        cerrar_conexion($con_mysqli);
                                    } else {
                                        echo "<div class='alert alert-warning' role='alert'>La imagen no se pudo subir correctamente.</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-warning' role='alert'>La imagen no es del tipo correcto. Solo se admiten imágenes de tipo 'jpg', 'jpeg', 'png' o 'gif'.</div>";
                                }
                            } else {
                                echo "<div class='alert alert-warning' role='alert'>El tamaño del fichero supera los 5MB</div>";
                            }
                        } else {
                            echo "<div class='alert alert-warning' role='alert'>La foto no se subió correctamente al formulario.</div>";                
                        }
                    } else {
                        echo "<div class='alert alert-warning' role='alert'>El método usado para eniviar el formulario no es el método POST.</div>";
                    }
                ?>
            </main>
        </div>
    </div>
    <?php include_once "footer.php"; ?>
</body>
</html>
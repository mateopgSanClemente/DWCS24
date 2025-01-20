<!DOCTYPE html>
<html lang="en">
<!-- HEAD -->
<?php
    include_once "head.php";
?>
<body>
    <!-- HEADER -->
    <?php
        include_once "header.php";
    ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- MENU -->
            <?php
                include_once "menu.php";
            ?>
            <!-- MAIN -->
             <main class="col-md-8 main-content">
                <div class="pt-4 pb-2 mb-3 border-bottom">
                    <h2>Resultado registro donante</h2>                   
                </div>
                <?php
                    require_once "utils.php";
                    // Guardar los datos del donante en variables
                    $nombre_donante = $_POST["nombre_donante"];
                    $apellido_donante = $_POST["apellido_donante"];
                    $edad_donante = $_POST["edad_donante"];
                    $grupo_sanguineo = $_POST["grupo_sanguineo"];
                    $codigo_postal = $_POST["codigo_postal"];
                    $telefono_movil = $_POST["telefono_movil"];

                    // Validar inputs e imprimir resultado
                    $resultado_validar = validar_donante($_POST);
                    if($resultado_validar === true){
                        // Eliminar caracteres especiales para evitar XSS
                        $nombre_donante = test_input($nombre_donante);
                        $apellido_donante = test_input($apellido_donante);
                        $edad_donante = test_input($edad_donante);
                        $grupo_sanguineo = test_input($grupo_sanguineo);
                        $codigo_postal = test_input($codigo_postal);
                        $telefono_movil = test_input($telefono_movil);

                        // Crear conexion
                        require_once "pdo.php";
                        $resultado_conexion = conexion_PDO("donacion");
                        if($resultado_conexion instanceof PDO){
                            // Insertar valores en la tabla
                            $resultado_insertar = insertar_donante($resultado_conexion, $nombre_donante, $apellido_donante, $edad_donante, $grupo_sanguineo, $codigo_postal, $telefono_movil);
                            // Comprobar que no se dieron errores
                            if($resultado_insertar[0]){
                                echo "<div class='alert alert-success' role='alert'>Se insertaron los datos en la tabla 'donante'.";
                            }else{
                                echo "<div class='aler alert-warning' role='aler'>Se produjo un error en la inserción: " . $resultado_insertar[1] . "</div>";
                            }
                        }else{
                            echo "<div class='alert alert-warning' role='alert'>$resultado_conexion</div>";
                        }
                    } else {
                        foreach($resultado_validar as $mensaje_error){
                            echo "<div class='alert alert-warning' role='alert'>" . $mensaje_error . "</div>";
                        }                 
                    }
                ?>
             </main>
        </div>
    </div>
</body>
</html>
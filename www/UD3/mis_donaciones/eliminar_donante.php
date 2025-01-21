<!DOCTYPE html>
<html lang="es">
<?php include_once "head.php";?>
<body>
   <?php include_once "header.php";?> 
   <div class="container-fluid d-flex flex-column">
        <div class="row">
            <?php include_once "menu.php";?>
            <main class="col-md-8 main-content">
                <div class="pt-4 pb-2 mb-3 border-bottom">
                    <h2>Eliminar donante</h2>
                </div>
                
                <?php
                    // Crear conexión PDO
                    require_once "pdo.php";
                    $con_PDO = conexion_PDO("donacion");
                    // Verificar que se creo correctamente
                    if($con_PDO instanceof PDO){
                        // Recoger el id del usuario que se desea eliminar
                        $id_usuario = $_GET['id_donante'];
                        // Eliminar usuario
                        list($comprobacion_eliminar, $resultado_eliminar) = eliminar_donante($con_PDO, $id_usuario);
                        // Comprobar que se eliminó correctamente el usuario
                        if($comprobacion_eliminar){
                            echo "<div class='alert alert-success' role='alert'>$resultado_eliminar</div>";
                        }else{
                            echo "<div class='alert alert-warning' role='alert'>$resultado_eliminar</div>";
                        }

                    }else{
                        echo "<div class='alert alert-warning' role='alert'>$con_PDO</div>";
                    }
                    // Cerrar conexión
                    $con_PDO = null;
                ?>
            </main>
        </div>
   </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
    <!-- HEAD -->
<?php
    include_once "head.php";
?>
<body>
    <!-- HEADER -->
    <?php
        include_once "header.php";
    ?>
    <div class="container-fluid d-flex-flex-comlun">
        <div class="row">
            <?php
                include_once "menu.php";
            ?>
            <!-- MAIN -->
            <main class="col-md-8 main-content">
                <div class="pt-4 pb-2 mb-3 border-bottom">
                    <h2>Registro de donación</h2>
                </div>
                <?php
                    // Recoger el id del donante
                    $id_donante = $_GET['id_donante'];
                    // Realizar la conexión PDO y comprobar que fue exitosa
                    require_once "pdo.php";
                    $resultado_con_PDO = conexion_PDO("donacion");
                    if($resultado_con_PDO instanceof PDO){
                        // Realizar la inserccion en la tabla historico
                        list($comprobacion, $resultado) = insertar_historico($resultado_con_PDO, $id_donante);
                        // Comprobar que la insercción se realizó correctamente
                        if($comprobacion){
                            echo "<div class='alert alert-success' role='alert'>Se insertó el histórico con id: $resultado</div>";
                        }else{
                            echo "<div class='alert alert-warning' role='alert'>Ocurrió un error en la inserción del historico de donación: $resultado</div>";
                        }
                    }else{
                        echo "<div class='aler alert-warning' role='alert'>$resultado_con_PDO</div>";
                    }
                    // Cerrar conexion
                    $resultado_con_PDO = null;
                ?>
            </main>
        </div>
    </div>
    <!-- FOOTER -->
    <?php
    include_once "footer.php";
    ?>
</body>
</html>
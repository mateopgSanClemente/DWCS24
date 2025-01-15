<!DOCTYPE html>
<html lang="es">
<!-- HEAD -->
<?php
    include_once "head.php";
?>
<body>
    <!-- HEADER -->
    <?php include_once "header.php"; ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- MENU -->
            <?php
                include_once "menu.php";
            ?>
            <!-- MAIN -->
            <main class="col-md-8 main-content">
                <div class="pt-4 pb-2 mb-3 border-bottom">
                    <h2>Sobre mí</h2>
                </div>
                <p>Mi nombre es <b>Mateo Pastor González</b> y estoy cursando la asignatura <b>DWCS</b>.</p>
                <div class="pt-4 pb-2 mb-3 border-bottom">
                    <h2>Variables de entorno</h2>
                </div>
                <p><?php echo $_ENV["MYSQL_USER"];?></p>
                <p><?php echo $_ENV["MYSQL_PASSWORD"];?></p>
                <p><?php echo $_ENV["MYSQL_ROOT_PASSWORD"];?></p>
                
                <?php
                    include_once ("pdo.php");
                    $conexion_PDO = conexion_PDO();
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
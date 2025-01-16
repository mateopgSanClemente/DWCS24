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
                <?php
                    include_once "pdo.php";
                    // Crear conexión PDO
                    $conexion_PDO = conexion_PDO();
                    // Crear base de datos
                    $resulado_crear_db = crear_db($conexion_PDO);
                    //Cerrar conexión
                    $conexion_PDO = null;

                    // Crear conexíon PDO a la base de datos 'donacion'
                    $conexion_PDO = conexion_PDO("donacion");
                    // Crear tabla donantes
                    $resultado_tabla_donantes = crear_tabla_donantes($conexion_PDO);
                    
                    // Crear tabla historico
                    $resultado_tabla_historico = crear_tabla_historico($conexion_PDO);

                    // Crear tabla administradores
                    $resultado_tabla_administradores = crear_tabla_administradores($conexion_PDO);
                    // Mostrar los resultados de la creación de la bd y la tabla en la página
                    echo "<div class='alert alert-warning' role='alert'>" . $resulado_crear_db . "</div>";
                    echo "<div class='alert alert-warning' role='alert'>" . $resultado_tabla_donantes . "</div>";
                    echo "<div class='alert alert-warning' role='alert'>" . $resultado_tabla_historico . "</div>";
                    echo "<div class='alert alert-warning' role='alert'>" . $resultado_tabla_administradores . "</div>";
                ?>
            </main>
        </div>
        <div class="row">
        <!-- FOOTER -->
        <?php
            include_once "footer.php";
        ?>
        </div>
    </div>

</body>
</html>
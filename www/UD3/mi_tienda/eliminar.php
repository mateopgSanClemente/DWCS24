<!DOCTYPE html>
<html lang="es">
<?php
    include_once "head.php";
?>
<body>

<!-- Header -->
<?php
    include_once "header.php";
?>
<!-- Layout -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php
            include_once "nav_menu.php";
        ?>
        <!-- Main Content -->
        <main class="col-md-9 main-content">

            <!-- Eliminar Usuario -->
            <section id="eliminar" class="mb-4">
                <h2>Eliminar Usuario</h2>
                
                    <?php
                        require_once("utils_bases_datos.php");
                        $conexion = conectar();
                        $id_usuario = $_GET['id'];
                        $resultado_eliminar = eliminar_cliente($conexion, $id_usuario);
                        //TODO: Indicar la información sobre el usuario eliminado y si se eliminó correctamente.
                        if ($resultado_eliminar[0])
                        {
                            echo ('<div class="alert alert-success" role="alert">' . $resultado_eliminar[1] . '</div>');
                        }
                        else if (!$resultado_eliminar[0])
                        {
                            echo ("<div class='aler alert-warning'>" . $resultado_eliminar[1] . "</div>");
                        }
                    ?>

            </section>
        </main>
    </div>
</div>

<!-- Footer -->
<?php
    include_once "footer.php";
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

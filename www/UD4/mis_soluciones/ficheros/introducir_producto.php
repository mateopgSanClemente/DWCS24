<!DOCTYPE html>
<html lang="es">
<?php
    include_once "head.php"
?>
<body>
    <?php
        include_once "header.php";
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php
                include_once "nav_menu.php";
            ?>
            
            <main class="col-md-9 main-content">
                <h2 class="mb-4">Introducir producto</h2>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nombre_producto" class="form-label">Nombre de producto</label>
                        <input type="text" name="nombre_producto" class="form-control" id="nombre_producto" placeholder="Nombre de producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_producto">Descripción producto</label>
                        <input type="text" name="descripcion_producto" class="form-control" id="descripcion_producto" placeholder="Descripción proucto" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio_producto" class="form-label">Precio producto</label>
                        <input type="number" name="precio_producto" id="precio_producto" class="form-control" placeholder="Precio producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="unidades_producto" class="form-label">Unidades producto</label>
                        <input type="number" name="unidades_producto" id="unidades_producto" class="form-control" placeholder="Unidades producto" requiered>
                    </div>
                    <div class="mb-3">
                        <label for="foto_producto" class="form-label">Foto producto</label>
                        <input type="file" name="foto_producto" id="foto_producto" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="submit" value="Subir producto" class="btn btn-success">
                    </div>
                </form>
            </main>
        </div>
    </div>
    <?php
        include_once "footer.php";
    ?>
</body>
</html>
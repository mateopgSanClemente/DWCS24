<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<?php
    include_once "head.php"
?>
<body class="d-flex flex-column min-vh-100">
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

                <?php
                    // Mostrar mensaje de error
                    if(isset($_SESSION["error"])){
                        echo "<div class='alert alert-warning' role='alert'>{$_SESSION["error"]}</div>";  
                        unset($_SESSION["error"]);  
                    } else if(isset($_SESSION["success"])){
                        echo "<div class='alert alert-success' role='alert'>{$_SESSION["success"]}</div>";
                        echo "<img src='mostrar_foto.php' class='img-fluid rounded shadow mx-auto d-block'>";
                        unset($_SESSION["success"]);
                    }
                ?>

                <form method="post" action="validar_producto.php" enctype="multipart/form-data">
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
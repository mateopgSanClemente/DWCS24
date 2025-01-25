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
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php
                include_once "nav_menu.php";
            ?>
            <!-- Layout -->
            <main class="col-md-9 main-content">
                <h2 class="mb-4">Sobre nuestra tienda</h2>
                <p class="descripcion">
                    Bienvenidos a la genuina e inigualable <strong>Tienda de Jabones PEPINO</strong>, los jabones más pepino de Compostela, un espacio dedicado a ofrecerte jabones, obviamente.
                    Tenemos una amplia gama de jabones a la venta con distribución tanto nacional a nivel España como internacional.
                    Disponemos de productos artesanales de la más alta calidad y otros que no tanto, algunos más democráticos que otros.
                </p>
                <p class="descripcion">   
                    Cada uno de nuestros productos está elaborado con ingredientes naturales, cuidando cada detalle para ofrecerte una experiencia única de cuidado personal, porque como comprenderás no te vamos a vender un jabón que huela mal.
                </p>
                <h2 class="mb-4">Sobre la administración de usuarios</h2>
                <p class="descripcion">
                    Al grano, utiliza la barra lateral de la izquierda para realizar distintas acción respecto a los usuarios y la base de datos. Desde esta podrás <strong>INICIAR LA BASE DE DATOS, MODIFICAR, ELIMINAR, REGISTRAR Y LISTAR</strong> a los usuarios.
                </p>
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
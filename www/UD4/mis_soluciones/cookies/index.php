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
            <?php
                // Recoger el valor de la cookie "idioma".
                require_once "cookies.php";
                // Guardar el idioma predeterminado para la cookie "idioma" en caso de que
                // no se haya establecido previamente
                if(isset($_COOKIE["idioma"])){
                    // Muestra un mensaje de bienvenida y de página principal en función del idioma almacenado en la cookie.
                    $idioma = $_COOKIE["idioma"];
                    $mensajeBienvenida = "";
                    $mensajeEncabezado = "";
                    switch ($idioma) {
                        case "gallego":
                            $mensajeEncabezado = "Páxina principal";
                            $mensajeBienvenida = "Benvido a páxina da nosa tenda.";
                            break;
                        case "castellano":
                            $mensajeEncabezado = "Página principal";
                            $mensajeBienvenida = "Bienvenido a la página princial de nuestra tienda.";
                            break;
                        case "ingles":
                            $mensajeEncabezado = "Main page";
                            $mensajeBienvenida = "Welcome to the main page of our shop.";
                            break;
                    }
                } else {
                    // Selecciona el idioma predeterminado que será el gallego
                    seleccionarIdiomaCookie();
                    $mensajeEncabezado = "Páxina principal";
                    $mensajeBienvenida = "Benvido a páxina da nosa tenda.";
                }

            ?>
            <!-- Layout -->
            <main class="col-md-9 main-content">
                <h2 class="mb-4"><?php echo $mensajeEncabezado; ?></h2>
                <p class="descripcion">
                    <?php echo $mensajeBienvenida; ?>
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
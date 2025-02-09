<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
?>
<?php include_once "head.php"; ?>
<body class='d-flex flex-column min-vh-100'>
    <!-- header -->
    <?php include_once 'header.php';?>
    <div class="container-fluid flex-grow-1">
        <div class="row">
            <!-- menu -->
            <?php
                include_once 'menu.php';
            ?>
            <main class="col-md-9 main-content">
                <div class="pt-4 pb-2 mb-3 border-bottom">
                    <h2>Sobre mí</h2>
                </div>
                <p>Pagina para la gestión de tareas y usuarios</p>
            </main>
        </div>

    </div>
    <!-- footer -->
    <?php include_once 'footer.php'; ?>
</body>
</html>
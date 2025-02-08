<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
?>
<?php include_once "head.php"; ?>
<body>
    <!-- header -->
    <?php include_once 'header.php';?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- menu -->
            <?php include_once "menu.php"; ?>
            <main class="col-md-9 main-content">
                <h2 class="border-bottom pt-4 pb-2 mb-3">Adjuntar archivo</h2>
                <form class="mb-5" action="subidaFichProc.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre del producto" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="descripcion">Descripcion</label>
                        <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="Descripción del producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="producto" class="form-label">Seleccionar archivo</label>
                        <input type="file" name="producto" id="producto" class="form-control">
                    </div>
                    <div class="mb-3">
                            <input type="submit" name="submit" value="Subir producto" class="btn btn-success">
                    </div>
                </form>
            </main>
        </div>
    </div>
</body>
</html>
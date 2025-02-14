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

                <?php
                // Mostrar mensajes de error
                if (!empty($_GET["error"]) && $_GET["error"] == true){
                    echo "<div class='alert alert-danger' role='alert'>Error al subir el fichero</div>";
                } else if (!empty($_GET["errorSize"]) && $_GET["errorSize"] == true){
                    echo "<div class='alert alert-warning' role='alert'>No se pudo subir el fichero, el tamaño no puede ser superior a 20 Mb</div>";
                } else if (!empty($_GET["errorType"]) && $_GET["errorType"] == true){
                    echo "<div class='alert alert-warning' role='alert'>No se pudo subir el fichero, sólo se admiten ficheros de tipo jpg, png y pdf.</div>";
                } else if (!empty($_GET["errorUpload"]) && $_GET["errorUpload"] == true){
                    echo "<div class='alert alert-warning' role='alert'>No se pudo subir el fichero a la careta files.</div>";
                }
                ?>
                <form class="mb-5" action="subidaFichProc.php?id=<?php echo $_GET["id"];?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre del fichero" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="descripcion">Descripcion</label>
                        <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="Descripción de la tarea">
                    </div>
                    <div class="mb-3">
                        <label for="fichero" class="form-label">Seleccionar archivo</label>
                        <input type="file" name="fichero" id="fichero" class="form-control">
                    </div>
                    <div class="mb-3">
                            <input type="submit" name="submit" value="Subir fichero" class="btn btn-success">
                    </div>
                </form>
            </main>
        </div>
    </div>
</body>
</html>
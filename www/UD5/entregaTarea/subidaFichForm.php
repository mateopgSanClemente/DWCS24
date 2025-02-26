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
                    if (isset($_SESSION["errPost"])){
                        echo "<div class='alert alert-warning' role='alert'>{$_SESSION["errPost"]}</div>";
                        unset($_SESSION["errPost"]);
                    }
                ?>
                <form class="mb-5" action="subidaFichProc.php?id=<?php echo $_GET["id"];?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre del fichero">
                    </div>
                    <?php
                    if(isset($_SESSION["err_fich_form"]["nombre"])){
                        foreach($_SESSION["err_fich_form"]["nombre"] as $error){
                            echo "<div class='alert alert-warning' role='alert'>$error</div>";
                        }
                    }
                    ?>
                    <div class="mb-3">
                        <label class="form-label" for="descripcion">Descripcion</label>
                        <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="Descripción de la tarea">
                    </div>
                    <?php
                    if(isset($_SESSION["err_fich_form"]["descripcion"])){
                        foreach($_SESSION["err_fich_form"]["descripcion"] as $error){
                            echo "<div class='alert alert-warning' role='alert'>$error</div>";
                        }
                    }
                    ?>
                    <div class="mb-3">
                        <label for="fichero" class="form-label">Seleccionar archivo</label>
                        <input type="file" name="fichero" id="fichero" class="form-control">
                    </div>
                    <?php
                    if(isset($_SESSION["err_fich_form"]["file"])){
                        foreach($_SESSION["err_fich_form"]["file"] as $error){
                            echo "<div class='alert alert-warning' role='alert'>$error</div>";
                        }
                    }
                    ?>
                    <div class="mb-3">
                            <input type="submit" name="submit" value="Subir fichero" class="btn btn-success">
                    </div>
                    <?php
                    // Eliminar la variable de sesión que recoger los errores del formulario en caso de que exista.
                    if(isset($_SESSION["err_fich_form"])){
                        unset($_SESSION["err_fich_form"]);
                    }
                    ?>
                </form>
            </main>
        </div>
    </div>
</body>
</html>
<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
    // Redirigir a index en caso de que la persona que pretende acceder lo haga sin sen administrador
    if ($_SESSION["rol"] !== 1) {
        header("Location: index.php");
        exit;
    }
?>
<?php include_once "head.php"; ?>
    <body>
        <!-- header -->
        <?php include_once "header.php";?>
          
        <div class='container-fluid d-flex flex-column'>
            <div class="row">
                <!-- menu -->
                <?php include_once "menu.php";?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Registrar Usuario</h2>
                    <?php
                        // Muestro los errores en la validación del formulario en caso de que existan
                        if (isset($_SESSION["errorVal"])){
                            // Crear una lista dinámica de mensajes con información sobre los errores asociados a un campo.
                            foreach ($_SESSION["errorVal"] as $nombre_campo => $errores) {
                                echo "<h4 class='pt-2 pb-4 mb-3 border-bottom'>Campo <b>$nombre_campo</b></h4>";
                                echo "<ul>";
                                foreach ($errores as $error) {                                
                                    echo "<li class='alert alert-warning' role='alert'>" . $error . "</li>";                                     
                                }
                                echo "</ul>";
                            }
                            unset($_SESSION["errorVal"]);
                        } else if (isset($_SESSION["errorConPDO"])){
                            echo "<div class='alert alert-danger'>" . $_SESSION["errorConPDO"] . "</div>";
                            unset($_SESSION["errorConPDO"]);
                        } else if (isset($_SESSION["errorInsUser"])){
                            echo "<div class='alert alert-warning'>" . $_SESSION["errorInsUser"] . "</div>";
                            unset($_SESSION["errorInsUser"]);
                        } else if (isset($_SESSION["success"])){
                            echo "<div class='alert alert-success'>" . $_SESSION["success"] . "</div>";
                            unset($_SESSION["success"]);
                        }
                    ?>
                    <section>
                        <!-- TODO: Incluir el nuevo campo 'rol' con usuario y administrados -->
                        <form action="nuevoUsuario.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username del usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos del usuario" required>
                            </div class="mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="rol">Rol</label>
                                <select class="form-select" name="rol" id="rol">
                                    <option value="" selected>Seleccione un rol</option>
                                    <option value="0">Usuario</option>
                                    <option value="1">Administrador</option>
                                </select>   
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Contraseña del usuario" required>
                            </div>
                            <button type="submit" class="btn btn-success mb-3">Registrar</button>
                        </form>
                    </section>
                </main>
            </div>
        </div>
        <!-- footer -->
        <?php include_once "footer.php";?>
    </body>
</html>
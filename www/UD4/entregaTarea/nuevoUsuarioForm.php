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
                                    <option value="" selected disabled>Seleccione un rol</option>
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
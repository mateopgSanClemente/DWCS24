<?php include_once "head.php"; ?>
<body class="d-flex flex-column min-vh-100">
    <?php include_once 'header.php'; ?>
    <div class="container-fluid d-flex flex-grow-1 align-items-center justify-content-center">
        <main class="col-md-9 main-content">
            <div class="card mx-auto" style="max-width: 400px;">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
                    <?php
                        // Comprobar que no hay errores
                        if (isset($_GET["error"])){
                            // Aviso en caso de que no se haya iniciado la sesión
                            if (($_GET["error"]) === "sesion") {
                                echo "<div class='alert alert-danger' role='alert'>" . "Es necesario iniciar la sesión" . "</div>";
                            }
                            // Aviso en caso de que no se pudiese conectar con la base de datos
                            if (($_GET["error"] === "conexion")) {
                                echo "<div class='alert alert-warning' role='alert'>" . "No se pudo conectar con la base de datos" . "</div>";
                            }
                            // Aviso en caso de que el usuario no exista
                            if (($_GET["error"] === "usuario")) {
                                echo "<div class='alert alert-warning' role='alert'>" . "No se encontró el usuario en la base de datos" . "</div>";
                            }
                            // Aviso en caso de que la contraseña no coincida.
                            if (($_GET["error"]) === "pass") {
                                echo "<div class='alert alert-warning' role='alert'>" . "La contraseña no es correcta" . "</div>";
                            }
                        }
                        // Mostrar que la sesión se cerró correctamente
                        if (isset($_GET["cerrar"]) && $_GET["cerrar"] == true) {
                            echo "<div class='alert alert-success' role='alert'>" . "La sesión se cerró correctamente" . "</div>";
                        }
                    ?>
                    <form action="loginAuth.php" method="post">
                        <!-- Campo para el username -->
                        <div class="form-group mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Ingresa tu usuario" required>
                        </div>

                        <!-- Campo para la contraseña -->
                        <div class="form-group mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Ingresa tu contraseña" required>
                        </div>

                        <!-- Botón de envío -->
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <?php include_once 'footer.php'; ?>
</body>
</html>
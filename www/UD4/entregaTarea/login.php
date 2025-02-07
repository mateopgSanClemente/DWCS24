<?php include_once "head.php"; ?>
<body class="d-flex flex-column min-vh-100">
    <?php include_once 'header.php'; ?>
    <div class="container-fluid d-flex flex-grow-1 align-items-center justify-content-center">
        <main class="col-md-9 main-content">
            <div class="card mx-auto" style="max-width: 400px;">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
                    <form action="login.php" method="post">
                        <!-- Campo para el username -->
                        <div class="form-group">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control mb-3" name="username" id="username" placeholder="Ingresa tu usuario" required>
                        </div>

                        <!-- Campo para la contraseña -->
                        <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control mb-3" name="password" id="password" placeholder="Ingresa tu contraseña" required>
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
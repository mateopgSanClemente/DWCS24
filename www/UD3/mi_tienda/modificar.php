<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuarios - Tienda de Jabones</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados (opcional) -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px;
            border-top: 1px solid #ddd;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .main-content {
            margin-top: 20px;
            padding-bottom: 100px;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <h1>Tienda de Jabones - Administración de Usuarios</h1>
    <p>Gestiona a los usuarios de forma sencilla.</p>
</header>

<!-- Layout -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-md-3 bg-light p-3">
            <h5 class="text-center">Menú</h5>
            <ul class="list-group">
                <li class="list-group-item"><a href="iniciar.php">Iniciar base de datos</a></li>
                <li class="list-group-item"><a href="registrar.php">Registrar Usuario</a></li>
                <li class="list-group-item"><a href="listar.php">Listar Usuarios</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="col-md-9 main-content">

            <!-- Modificar Usuario -->
            <section id="modificar" class="mb-4">
                <h2 class="mb-4">Modificar Usuario</h2>
                <?php
                    echo "<div class='alert alert-warning' role='alert'>Modificando el usuario: " . $_GET['nombre'] . " " . $_GET['apellido'] . "</div>";
                ?>
                <h2 class="mb-4">Formulario</h2>
                <form method="post" action=<?php echo "validar_modificar.php?id=" . $_GET['id'] . "\""?>>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nuevo Nombre de Usuario</label>
                        <input name="nombre" type="text" class="form-control" id="nombre" placeholder="Nuevo nombre de usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Nuevos Apellidos de Usuario</label>
                        <input name="apellidos" type="text" class="form-control" id="apellidos" placeholder="Nuevos apellidos de usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="edad" class="form-label">Nueva Edad de Usuario</label>
                        <input name="edad" type="number" class="form-control" id="edad" placeholder="Nueva edad de usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="provincia" class="form-label" class="form-label">Nueva Provincia de Usuario</label>
                        <select name="provincia" id="provincia" class="form-select" required>
                            <option value="" disabled selected>Selecciona una provincia</option>
                            <option value="pontevedra">Pontevedra</option>
                            <option value="a corunha">A Coruña</option>
                            <option value="lugo">Lugo</option>
                            <option value="ourense">Ourense</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </form>
            </section>
        </main>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Tienda de Jabones. Todos los derechos reservados.</p>
    <a href ="index.php" class="btn btn-primary">Página principal</a>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
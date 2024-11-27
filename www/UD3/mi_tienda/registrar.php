<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuarios - Tienda de Jabones</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados, debería pasarlo a un fichero CSS a parte (externo), en lugar de dejarlo como un estilo externo. -->
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
    <h1>Tienda de Jabones - Registro de Usuarios</h1>
    <p>Registra a los usuarios de forma sencilla.</p>
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
            <!-- Registrar Usuario -->
            <section id="registrar" class="mb-4">
                <h2>Registrar Usuario</h2>
                <form method="post" action="validar_registrar.php">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del cliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos del cliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="edad" class="form-label">Edad</label>
                        <input type="number" class="form-control" name="edad" id="edad" placeholder="Edad del cliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="provincia" class="form-label">Provincia</label>
                        <select class="form-select" name="provincia" id="provincia" required>
                            <option value="" disabled selected>Seleccione una provincia</option>
                            <option value="pontevedra">Pontevedra</option>
                            <option value="a corunha">A Coruña</option>
                            <option value="lugo">Lugo</option>
                            <option value="ourense">Ourense</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </section>
        </main>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Jabones Pepino. Todos los derechos reservados.</p>
    <a href ="index.php" class="btn btn-primary">Página principal</a>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
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

        .descripcion {
            text-align: justify;
            padding-right: 20px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <h1>Tienda de Jabones - Administración de Usuarios</h1>
        <p>Gestiona a los usuarios de forma sencilla.</p>
    </header>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3 bg-light p-3">
                <h5 class="text-center">Menú</h5>
                <ul class="list-group">
                    <li class="list-group-item"><a href="iniciar.html">Iniciar base de datos</a></li>
                    <li class="list-group-item"><a href="registrar.html">Registrar Usuario</a></li>
                    <li class="list-group-item"><a href="listar.html">Listar Usuarios</a></li>
                    <li class="list-group-item"><a href="modificar.html">Modificar Usuario</a></li>
                    <li class="list-group-item"><a href="eliminar.html">Eliminar Usuario</a></li>
                </ul>
            </aside>
            <!-- Layout -->
            <main class="col-md-9 main-content">
                <h2 class="mb-4">Sobre nuestra tienda</h2>
                <p class="descripcion">
                    Bienvenidos a la genuina e inigualable <strong>Tienda de Jabones PEPINO</strong>, los jabones más pepino de Compostela, un espacio dedicado a ofrecerte jabones, obviamente.
                    Tenemos una amplia gama de jabones a la venta con distribución tanto nacional a nivel España como internacional.
                    Disponemos de productos artesanales de la más alta calidad y otros que no tanto, algunos más democráticos que otros.
                </p>
                <p class="descripcion">   
                    Cada uno de nuestros productos está elaborado con ingredientes naturales, cuidando cada detalle para ofrecerte una experiencia única de cuidado personal, porque como comprenderás no te vamos a vender un jabón que huela mal.
                </p>
                <h2 class="mb-4">Sobre la administración de usuarios</h2>
                <p class="descripcion">
                    Al grano, utiliza la barra lateral de la izquierda para realizar distintas acción respecto a los usuarios y la base de datos. Desde esta podrás <strong>INICIAR LA BASE DE DATOS ,MODIFICAR, ELIMINAR, REGISTRAR Y LISTAR</strong> a los usuarios usuario.
                </p>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Tienda de Jabones. Todos los derechos reservados.</p>
        <a href="index.php" class="btn btn-primary">Página principal</a>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
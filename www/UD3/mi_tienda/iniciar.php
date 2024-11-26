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
                    <li class="list-group-item"><a href="iniciar.php">Iniciar base de datos</a></li>
                    <li class="list-group-item"><a href="registrar.html">Registrar Usuario</a></li>
                    <li class="list-group-item"><a href="listar.html">Listar Usuarios</a></li>
                    <li class="list-group-item"><a href="modificar.html">Modificar Usuario</a></li>
                    <li class="list-group-item"><a href="eliminar.html">Eliminar Usuario</a></li>
                </ul>
            </aside>
            <!-- Layout -->
            <main class="col-md-9 main-content">
                <?php
                    include_once ("utils_bases_datos.php");

                    //Crear base de datos
                    $conexion = conectar("db", "root", "test", null);

                    $creacion_base_datos = crear_base_datos ($conexion);

                    if ($creacion_base_datos[0] === false)
                    {
                        echo '<div class="alert alert-warning" role="alert">' . $creacion_base_datos[1] . '</div>';
                    }
                    else if ($creacion_base_datos[0] === true)
                    {
                        echo '<div class="alert alert-success" role="alert">' . $creacion_base_datos[1] . '</div>';
                    }

                    //Crear tabla 'clientes'
                    $conexion = conectar();
                    
                    $tabla_usuarios = crear_tabla($conexion);

                    if ($tabla_usuarios[0] === true)
                    {
                        echo '<div class="alert alert-success" role="alert">' . $tabla_usuarios[1] . '</div>';
                    }
                    else if ($tabla_usuarios[0] === false)
                    {
                        echo '<div class="alert alert-warning" role="alert">' . $tabla_usuarios[1] . '</div>';
                    }
                ?>
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
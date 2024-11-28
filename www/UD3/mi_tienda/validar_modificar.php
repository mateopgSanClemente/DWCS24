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
            <h2>Estado de la modificación</h2>
            <?php
                //Recoger los valores del formulario (POST) y el id (GET)
                require_once("utils_validar.php");

                $id = $_GET['id'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellidos'];
                $edad = $_POST['edad'];
                $provincia = $_POST['provincia'];

                //Validar los datos
                $error = false;
                $mensaje_error = [];
                //Validar nombre
                if(!comprobar_campo($nombre))
                {
                    $error = true;
                    $mensaje_error[] = "El campo 'nombre' no puede estar vacio y debe contener por lo menos un caracter alfabético."; 
                }
                //Validar apellidos
                if(!comprobar_campo($apellido))
                {
                    $error = true;
                    $mensaje_error[] = "El campo 'apellidos' no puede estar vacio y debe contener por lo menos un caracter alfabético.";
                }
                //Validar edad
                if(!comprobar_campo($edad))
                {
                    $error = true;
                    $mensaje_error[] = "El campo 'edad' no puede estar vacío y debe ser un número que se encuentre entre los valores 18 y 130.";
                }
                //Validar provincia
                if(!comprobar_campo($provincia))
                {
                    $error = true;
                    $mensaje_error = "El campo 'provincia' no puede estar vacio y debe contener por lo menos un caracter alfabético.";
                }

                foreach ($mensaje_error as $mensaje)
                {
                    echo "<div class='alert alert-warning' role='alert'>" . $mensaje . "</div>";
                }

                if(!$error)
                {
                    require_once("utils_bases_datos.php");

                    $conexion = conectar();
                    $resultado = modificar_cliente($conexion, $id, $nombre, $apellido, $edad, $provincia);

                    if ($resultado[0] === true)
                    {
                        echo "<div class='alert alert-success role='alert'>" . $resultado[1] . "</div>";
                    }
                    else if ($resultado[0] === false)
                    {
                        echo "<div class='alert alert-success role='alert'>" . $resultado[1] . "</div>";
                    }
                    
                }
            ?>
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
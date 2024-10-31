<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD2. Tarea - Comprobaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!--header-->
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!--menu-->
            <?php include 'menu.php'; ?>           
            <div class="col">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Obtener datos del formulario
                    $id_tarea = $_POST['id_tarea'];
                    $nombre_tarea = $_POST['nombre_tarea'];
                    $estado_tarea = $_POST['estado_tarea'];

                    // Inicializar un array para errores
                    $errores = [];

                    // Validaciones
                    if (empty($id_tarea)) {
                        $errores[] = "El campo 'Id tarea' es obligatorio.";
                    }
                    if (empty($nombre_tarea)) {
                        $errores[] = "El campo 'Nombre tarea' es obligatorio.";
                    }
                    if (empty($estado_tarea)) {
                        $errores[] = "Debes seleccionar un estado para la tarea.";
                    }

                    // Mostrar errores
                    if (!empty($errores)) {
                        echo "<div class='alert alert-danger'>";
                        foreach ($errores as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div>";
                    } else {
                        // Si no hay errores, mostrar los datos
                        echo "<div class='alert alert-success'>";
                        echo "<p>";
                        echo "Tarea creada exitosamente:<br>";
                        echo "ID: $id_tarea<br>";
                        echo "Nombre: $nombre_tarea<br>";
                        echo "Estado: $estado_tarea<br>";
                        echo "</p>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    
    <!--footer-->
    <?php include 'footer.php'; ?>
</body>
</html>
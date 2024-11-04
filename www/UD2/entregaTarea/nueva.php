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
            <!--Formulario-->
            <!--Mi idea inicial era añadir el atributo required a algunos de los campos del formulario, pero lo eliminé con el fin de validarlos a través del fichero nueva.php y las funciones del fichero utils.php-->
                <form class="mb-5" action="nueva.php" method="post">
                    <div class="mb-3">
                        <label class="form-label">Id tarea:</label>
                        <!--Podría acotar el rango de números válidos para este campo-->
                        <input class="form-control" type="number" name="id_tarea">
                    </div>
                    <div class="mb-3">
                        <!-- Podría poner un mínimo y un máximo de caracteres para este campo -->
                        <label class="form-label">Nombre:</label>
                        <input class="form-control" type="text" name="nombre_tarea">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado:</label>
                        <select class="form-select" name="estado_tarea">
                            <option value="" selected>Selecciona un estado</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En proceso">En proceso</option>
                            <option value="Completada">Completada</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>     
            <div class="col">
                <?php
                include 'utils.php';
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Obtener datos del formulario
                    $id_tarea = $_POST['id_tarea'];
                    $nombre_tarea = $_POST['nombre_tarea'];
                    $estado_tarea = $_POST['estado_tarea'];

                    // Inicializar un array para errores
                    $errores = [];

                    // Validaciones mediante las funciones del fichero utils.php
                    if (!comprobar_campo($id_tarea)) {
                        $errores[] = "El campo 'Id tarea' es obligatorio.";
                    }
                    if (!comprobar_campo($nombre_tarea)) {
                        $errores[] = "El campo 'Nombre tarea' es obligatorio.";
                    }
                    if (!comprobar_campo($estado_tarea)) {
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
            <div class="col">
                <?php
                    include 'listaTareas.php';
                ?>
            </div>
        </div>
    </div>
    
    <!--footer-->
    <?php include 'footer.php'; ?>
</body>
</html>
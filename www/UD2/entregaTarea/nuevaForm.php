<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD2. Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!--header-->
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!--menu-->
            <?php include 'menu.php'; ?>
            
            <!--Formulario-->
            <!--Mi idea inicial era añadir el atributo required a algunos de los campos del formulario, pero lo eliminé con el fin de validarlos a través del fichero nueva.php y las funciones del fichero utils.php-->
            <form class="mb-5" action="nueva.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Id tarea:</label>
                    <!--Podría acotar el rango de números válidos para este campo-->
                    <input class="form-control" type="number" name="id_tarea">
                </div>
                <div class="mb-3">
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
    </div>
    
    <!--footer-->
    <?php include 'footer.php'; ?>
</body>
</html>
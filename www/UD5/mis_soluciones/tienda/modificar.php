<!DOCTYPE html>
<html lang="es">
<?php
    include_once "head.php";
?>
<body class="d-flex flex-column min-vh-100">

<!-- Header -->
<?php
    include_once "header.php";
?>

<!-- Layout -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php
            include_once "nav_menu.php";
        ?>

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
<?php
    include_once "footer.php";
?>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
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
                    <button type="submit" class="btn btn-success">Registrar</button>
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
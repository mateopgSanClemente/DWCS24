<!DOCTYPE html>
<html lang="en">
    <!-- HEAD -->
<?php
    
    include_once "head.php";
?>
<body>
    <!-- HEADER -->
    <?php
        include_once "header.php";
    ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- MENU -->
            <?php
                include_once "menu.php";
            ?>
            <!-- MAIN -->
             <!-- Hay algunos aspectos del formulario que podría restringir desde el fichero html,
              voy a hacerlo mediante validación por funciones -->
            <main class="col-md-8 main-content">
                <h2 class="pt-4 pb-2 mb-3 border-bottom">Registro de donante</h2>
                <form action="registrar_usuario.php" method="POST">
                    <div class="mb-3">
                        <label for="nombre_donante" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre_donante" id="nombre_donante" placeholder="Nombre del donante" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_donante" class="form-label">Apellidos:</label>
                        <input type="text" class="form-control" name="apellido_donante" id="apellidos_donante" placeholder="Apellidos del donante" requiered>
                    </div>
                    <div class="mb-3">
                        <label for="edad_donante" class="form-label">Edad:</label>
                        <input type="number" class="form-control" name="edad_donante" id="edad_donante" required>
                    </div>
                    <div class="mb-3">
                        <label for="grupo_sanguineo" class="form-label">Grupo sanguineo:</label>
                        <select name="grupo_sanguineo" id="grupo_sanguineo" class="form-select" required>
                            <option value="" selected disabled>Grupo sanguineo del donante</option>
                            <option value="0-">0-</option>
                            <option value="0+">0+</option>
                            <option value="A-">A-</option>
                            <option value="A+">A+</option>
                            <option value="B-">B-</option>
                            <option value="B+">B+</option>
                            <option value="AB-">AB-</option>
                            <option value="AB+">AB+</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="codigo_postal" class="form-label">Código postal:</label>
                        <input type="text" class="form-control" name="codigo_postal" id="codigo_postal" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono_movil" class="form-label">Teléfono móvil:</label>
                        <input type="text" class="form-control" name="telefono_movil" id="telefono_movil" required>
                    </div>
                </form>
            </main>
        </div>
    </div>
    <!-- FOOTER -->
    <?php
        include_once "footer.php";
    ?>
</body>
</html>
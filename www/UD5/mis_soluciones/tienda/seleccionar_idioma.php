<?php
    // Variable que recoge mensajes de error
    $mensajeError = "";
    // Procesar formulario en caso de que se haya enviado el formulario.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "cookies.php";
        // Usar el operador null-coalescing para comprobar que se envió correctamente el formulario
        $idioma = $_POST["idioma"] ?? "";
        $resultadoValidar = seleccionarIdiomaCookie($idioma);
        // Comprobar que el valor de la cookies se guardó correctamente
        if (!$resultadoValidar){
            // Mostrar un mensaje informando de que el idioma introducido no es válido.
            $mensajeError = "El idioma seleccionado no es válido.";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once "head.php";?>
<body>
    <?php include_once "header.php";?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once "nav_menu.php";?>
            <main class="col-md-9 main-content">
                <h2 class="mb-4">Seleccionar idioma</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <label for="idioma" class="form-label">Idioma</label>
                    <select name="idioma" id="idioma" class="form-select" required>
                        <option value="" disabled selected>Selecciona un idioma</option>
                        <option value="gallego">Gallego</option>
                        <option value="castellano">Castellano</option>
                        <option value="ingles">Inglés</option>
                    </select>
                    <button type="submit" class="btn btn-success mt-4">Guardar Idioma</button>
                </form>
            </main>
        </div>
    </div>
    <?php include_once "footer.php";?>;
</body>
</html>
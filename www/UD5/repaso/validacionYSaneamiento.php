<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar y Sanear</title>
</head>
<body>
    <?php
        /** Validar formulario */
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            array_map("htmlspecialchars", $_POST);
            if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                echo "<p>El email es válido</p>";
            } else {
                echo "<p>El email NO es válido</p>";
            }

            if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $_POST["nombre"])){
                echo "<p>El nombre es válido</p>";
            } else {
                echo "<p>El nombre NO es válido</p>";
            }
        }
    ?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <label for="email">Email</label>
        <input type="email" name="email" required>
        <label for="comentario">Comentario</label>
        <textarea name="mensaje"></textarea>
        <input type="submit" value="Subir formulario">
    </form>
</body>
</html>
<?php
    if (!empty($_POST)){
        if ($_POST["nombre"] == "usuario" && $_POST["password"] == "pass") {
            session_start();
            session_regenerate_id(true);
            setcookie("nombre", $_POST["nombre"], time() + 86400 * 7, "/");
            $_SESSION["nombre"] = $_POST["nombre"];
        }
    } if (!empty($_GET) && $_GET["cerrar"] == true) {
        session_start();
        session_unset();
        session_destroy();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesiones y cookies</title>
</head>
<body>
    <?php
    /**
     * Ejercicio:
     * Crea un sistema de autenticación simple usando sesiones y cookies.
     *
     * Pasos:
     *
     *  Crea un formulario de inicio de sesión con usuario y contraseña.
     *  Si las credenciales son correctas, inicia sesión usando $_SESSION y almacena el nombre del usuario.
     *  Usa una cookie para recordar al usuario durante 7 días.
     *  Si el usuario cierra la sesión, destruye la sesión y elimina la cookie.
     */
    if (!empty($_POST)){
        if ($_POST["nombre"] == "usuario" && $_POST["password"] == "pass") {
            echo "<p>Se accedió con el usuario '{$_SESSION["nombre"]}'.</p>";
            echo "<form action={$_SERVER['PHP_SELF']} method='GET'>";
            echo "<input type='hidden' name='cerrar' value='true'>";
            echo "<input type='submit' value='Cerrar sesion'>";
            echo "</form>";
            exit;
        }
    }
    ?>
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method='POST'>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre">
        <label for="password">Contraseña</label>
        <input type="password" name="password">
        <input type="submit" value="Acceder">
    </form>
</body>
</html>
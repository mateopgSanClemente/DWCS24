<?php
session_start();
// Redirige al usuario al formulario de login en caso de que la sesión no exista.
if (!isset($_SESSION["usuario"])){
    header("Location: login.php?error=sesion");
    exit;
}

// Crear cookie, guardar el valor que reciba desde el formulario del menú
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SERVER["HTTP_REFERER"])) {
    $tema = $_POST["tema"];
    // Validar formulario
    $temas_permitidos = ["light", "dark", "auto"];
    if (in_array($tema, $temas_permitidos)){
        setcookie("tema", $tema, time() + 86400, "/", "", false, false);
        // Redirigir a la página desde la que se envió el formulario
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
<?php
    session_start();
    /**
     *  TODO:
     *  - Verificar que la sesión está activa antes de cerrarla.
     *  - Invalidar cookies de sesión si es que existen.
     */
    // Eliminar todas las variables de sesión
    session_unset();
    // ? Regenerar el ID de sesión antes de destruirla para mayor seguridad
    session_regenerate_id(true);
    // Destruye la sesión
    session_destroy();
    // Redireccionar a la página de login
    header ("Location: login.php?cerrar=true");
    exit;
?>
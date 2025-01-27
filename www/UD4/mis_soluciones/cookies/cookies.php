<?php
    /**
     * Crear una cookies mediante la función setcookie:
     *      Ej- setcookie(nombre, valor, time() + 86400 * dias, /)
     */

    function contarVisitaCookie(){
        $visitas = 1; // Valor inicial para la primera visita
        if(isset($_COOKIE["visitas"])){
            // Validar que el valor de la cookie sea un número
            $visitas = is_numeric($_COOKIE["visitas"]) ? intval($_COOKIE["visitas"]) + 1 : 1;
        }
        // Guardar el valor en la cookies
        setcookie("visitas", $visitas, time() + 86400 * 10, "/");
        // Retornar el número de visitas
        $visitas;
    }
?>
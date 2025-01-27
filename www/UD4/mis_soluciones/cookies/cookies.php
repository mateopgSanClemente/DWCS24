<?php
    /**
     * Contabiliza el número de visitas a la página utilizando cookies.
     * 
     * Esta función registra y actualiza una cookie denominada "visitas" para 
     * realizar el conteo de las visitas del usuario a la página. Si la cookie 
     * no existe, se inicializa con el valor 1. Si existe, se valida que su 
     * contenido sea numérico y se incrementa en 1. El valor de la cookie se 
     * guarda con una duración de 10 días.
     *
     * @return int El número total de visitas actualizado.
     *
     * @example
     * // Ejemplo de uso:
     * $visitas = contarVisitaCookie();
     * echo "Has visitado esta página $visitas veces.";
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
        return $visitas;
    }

    /**
     * Seleccionar el idioma de la página.
     * 
     * Registra y actualiza la cookie "idioma" para seleccionar el idioma
     * en el cual se mostrará la página.
     * 
     * @param string $idioma Idioma de la página.
     * @return boolean Devuelve true si la cookie se guardó correctamente y false
     * si no.
     */
    function seleccionarIdiomaCookie(string $idioma){
        // Lista de idioma válidos
        $idiomaValido = ["gallego", "castellano", "ingles"];
        // Validar si el idioma es válido
        if (in_array(strtolower($idioma), $idiomaValido)){
            // Establecer el valor para la cookie
            setcookie("idioma", $idioma, 86400 * 10, "/");
            // Mostrar que el valor de la cookie se guardó correctamente
            return true;
        }
        return false;
    }
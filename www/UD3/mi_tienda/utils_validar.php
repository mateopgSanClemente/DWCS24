<?php

    /**
     * Función para eliminar los espacios en blanco sobrantes en los extremos de una cadena de texto, eliminar las barras invertidas y codificar caracteres especiales de HTML.
     * @param string $input cadena de caracteres a procesar.
     * @return string cadena de caracteres codificada.
     */
    function test_input ($input)
    {
        $input = trim ($input);
        $input = stripcslashes ($input);
        $input = htmlspecialchars ($input);
        return $input;
    }


    /**
     * Funcion para validar el campo.
     * - Si el campo es alfanumérico, valida el formato.
     * - Si el campo es numérico, se asegura que esté dentro del rango de 18 y 130, inclusive.
     * @param string $campo valor recivido a la hora de enviar el formulario.
     */
    function comprobar_campo ($campo)
    {
        //Empiezo limpiando la entrada
        $campo = test_input ($campo);

        //Si el campo está vacio devuelvo false
        if (empty($campo))
        {
            return false;
        }
        /* Este 'if' sobra, en este caso todos los datos del formulario se envían como tipo string.
        if (is_string($campo))
        {
            
        }
        */

        //Para los campos que pretender ser una cadena de caracteres solo se permitiran caracteres alfabéticos, seguido de un espacio opcional y otro conjunto de caracteres igual al primero, también opcional
        //¿Realmente necesito comprobar que el campo no es un número si ya lo estoy haciendo a través de la expresión regular?
        //TODO: R
        if (!is_numeric($campo) && preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+( [a-zA-ZáéíóúÁÉÍÓÚñÑ]+)*$/", $campo))
        {
            return true;
        } 
        
        if (is_numeric($campo))
        {
            //Convierto el numero que esta como un tipo string a un tipo numérico
            //$numero = intval($campo);
            // ^ No es necesario ya que php puede tratar la cadena como un número al utilizar operadores comparativos.
            //Valido el valor del numero
            if ($campo >= 18 && $campo <= 130) {
                return true;
            }
        }

        //En caso de no pasar ninguna de las validaciones
        return false;
    }
?>
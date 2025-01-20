<?php

    function test_input($input){
        $input = trim ($input);
        $input = stripcslashes ($input);
        $input = htmlspecialchars ($input);
        return $input;
    }
    /**
     * 
     */
    function validar_donante(array $datos_donante){
        // Guardar los datos del donante en variables
        $nombre_donante = $datos_donante["nombre_donante"];
        $apellido_donante = $datos_donante["apellido_donante"];
        // Convertir la edad a tipo entero
        $edad_donante = intval($datos_donante["edad_donante"]);
        $grupo_sanguineo = $datos_donante["grupo_sanguineo"];
        $codigo_postal = $datos_donante["codigo_postal"];
        $telefono_movil = $datos_donante["telefono_movil"];

        // Array con los posibles errores
        $array_errores = [];

        // 1º- VALIDAR CAMPOS

        // Validar nombre
        // Campo obligatorio
        if(empty($nombre_donante)){
            $array_errores[] = "El campo 'nombre' es olbigatorio.";
        }
        // Número máximo de caracteres
        if(strlen($nombre_donante) > 100){
            $array_errores[] = "El campo 'nombre' no debe contener más de 100 caracteres.";
        }
        // Caracteres permitidos
        if(!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-']+$/", $nombre_donante)){
            $array_errores[] = "El campo 'nombre' solo puede contener caracteres de la 'a' a la 'z', espacios y guiones.";
        }

        // Validar apellido
        // Campo obligatorio
        if(empty($apellido_donante)){
            $array_errores[] = "El campo 'apellido' es olbigatorio.";
        }
        // Número máximo de caracteres
        if(strlen($apellido_donante) > 150){
            $array_errores[] = "El campo 'apellido' no debe contener más de 100 caracteres.";
        }
        // Caracteres permitidos
        if(!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-']+$/", $nombre_donante)){
            $array_errores[] = "El campo 'apellido' solo puede contener caracteres de la 'a' a la 'z', espacios y guiones.";
        }

        // Validar edad donante
        // Campo obligatorio
        if(empty($edad_donante)){
            $array_errores[] = "El campo 'edad' es obigatorio.";
        }
        // Comprobar tipo
        if(!is_numeric($edad_donante)){
            $array_errores[] = "El campo 'edad' debe ser un número.";
        }
        // Comprobar que la edad es mayor a 18.
        if($edad_donante < 18){
            $array_errores[] = "El campo 'edad' debe ser mayor a 18.";
        }

        // Validar grupo sanguineo
        // Campo obligatorio
        if(empty($grupo_sanguineo)){
            $array_errores = "El campo 'grupo sanguineo' es obligatorio.";
        }
        // Comprobar que el valor es correcto
        // Otra opción podría ser utilizar expresiones regulares.
        $validar_grupo = false;
        $array_grupo_sanguineo = ["0-", "0+", "A-", "A+", "B-", "B+", "AB-", "AB+"];
        foreach ($array_grupo_sanguineo as $grupo_valido){
            if($grupo_sanguineo === $grupo_valido){
                $validar_grupo = true;
                break;
            }
        }
        if($validar_grupo === false){
            $array_errores[] = "El grupo sanguineo no es válido.";
        }

        // Código postal
        // Campo obligatorio
        if(empty($codigo_postal)){
            $array_errores[] = "El campo 'código postal' es obligatorio.";
        }
        // Comprobar que solo recoge números y como máximo 5 caracteres
        if(!preg_match("/^\d{5}$/", $codigo_postal)){
            $array_errores[] = "El código postal solo puede contener númerosy un máximo de 5 caracteres.";
        }

        // Teléfono móvil
        // Campo obligatorio
        if(empty($telefono_movil)){
            $array_errores[] = "El campo 'teléfono' es obligatorio.";
        }
        // Comprobar que solo recoge números y como máximo 9 caracteres
        if(!preg_match("/^\d{9}$/", $telefono_movil)){
            $array_errores[] = "El teléfono móvil solo puede contener números y un máximo de 9 caracteres.";
        }

        if(empty($array_errores)){
            return true;
        } else {
            return $array_errores;
        }
    }
?>
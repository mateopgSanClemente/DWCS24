<?php
    //Funciones para la tarea UD3
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

    /**
     * Valida los campos del formulario para el registro de usuarios.
     * 
     * @param string $campo Campo del furmulario para el registro de usuarios.
     * @param string 
     * @return bool  Retorna un booleano que indica el éxito de la validación
     *               (true si se validó correctamente el campo, false en caso de error)
     */
    function validar_usuario ($username, $nombre, $apellidos, $contrasena)
    {
        // Validar campos
        $errores = [];
        // Validar `username`: No vacío y máximo de 50 caracteres
        if (empty($username) || strlen($username) > 50) {
            $errores[] = "El campo 'username' es obligatorio y no puede exceder los 50 caracteres.";
        }

        // Validar `nombre`: No vacío y máximo de 50 caracteres
        if (empty($nombre) || strlen($nombre) > 50) {
            $errores[] = "El campo 'nombre' es obligatorio y no puede exceder los 50 caracteres.";
        }

        // Validar `apellidos`: No vacío y máximo de 100 caracteres
        if (empty($apellidos) || strlen($apellidos) > 100) {
            $errores[] = "El campo 'apellidos' es obligatorio y no puede exceder los 100 caracteres.";
        }

        // Validar `contrasena`: No vacío y máximo de 100 caracteres
        if (empty($contrasena) || strlen($contrasena) > 100) {
            $errores[] = "El campo 'contraseña' es obligatorio y no puede exceder los 100 caracteres.";
        }
        
        //Si hay errores, devolverlos
        if(!empty($errores))
        {
            return [true, implode(' ', $errores)];
        }
    }

    /*Función para validar tareas
    */
    function validar_tarea ($titulo, $descripcion, $estado, $id_usuario)
    {
        // Validar campos
        $errores = [];
        
        // Validar título
        if (empty($titulo) || strlen($titulo) > 50) {
            $errores[] = "El título es obligatorio y no debe exceder los 50 caracteres.";
        }
        
        // Validar descripción (puede ser nula)
        if (!is_null($descripcion) && strlen($descripcion) > 250) {
            $errores[] = "La descripción no debe exceder los 250 caracteres.";
        }
        
        // Validar estado
        $estados_validos = ['Pendiente', 'En proceso', 'Completada'];
        if (empty($estado) || !in_array($estado, $estados_validos)) {
            $errores[] = "El estado es obligatorio y debe ser uno de los siguientes: " . implode(', ', $estados_validos) . ".";
        }
        
        // Validar id_usuario
        if (empty($id_usuario) || !is_numeric($id_usuario)) {
            $errores[] = "El ID del usuario es obligatorio y debe ser un número entero válido.";
        }
        
        // Si hay errores, devolverlos
        if (!empty($errores)) {
            return [true, implode(' ', $errores)];
        }
    }
    
?>
<?php
    //Funciones para la tarea UD3
    /**
     * Función para eliminar los espacios en blanco sobrantes en los extremos de una cadena de texto, eliminar las barras invertidas y codificar caracteres especiales de HTML.
     * @param string $input cadena de caracteres a procesar.
     * @return string cadena de caracteres codificada.
     */
    function test_input ($input) {
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
     * Valida los datos de un usuario antes de su creación.
     *
     * Verifica que los campos `username`, `nombre`, `apellidos` y `contrasena` no estén vacíos
     * y que cumplan con las restricciones de longitud establecidas.
     *
     * @param string $username   Nombre de usuario (obligatorio, máx. 50 caracteres).
     * @param string $nombre     Nombre del usuario (obligatorio, máx. 50 caracteres).
     * @param string $apellidos  Apellidos del usuario (obligatorio, máx. 100 caracteres).
     * @param string $contrasena Contraseña del usuario (obligatorio, máx. 100 caracteres).
     *
     * @return array<mixed> Retorna un array asociativo con la siguiente información:
     *                      - "success" (bool): true si la validación es exitosa, false en caso contrario.
     *                      - "errores"? (string): Si hay errores, incluyendo un array asociativo "errores".
     * 
     */
    function validar_usuario (string $username, string $nombre, string $apellidos, string $contrasena) : array {
        $errores = [
            "username" => [],
            "nombre" => [],
            "apellidos" => [],
            "contrasena" => []
        ];
        // Validar `username`: No vacío y máximo de 50 caracteres
        if (empty($username)) {
            $errores["username"][] = "El campo 'username' es obligatorio.";
        }
        if (strlen($username) > 50) {
            $errores["username"][] = "No puede exceder los 50 caracteres.";
        }
        // Validar `nombre`: No vacío y máximo de 50 caracteres
        if (empty($nombre)) {
            $errores["nombre"][] = "El campo 'nombre' es obligatorio";
        }
        if (strlen($nombre) > 50) {
            $errores["nombre"][] = "No puede exceder los 50 caracteres.";
        }
        // Validar `apellidos`: No vacío y máximo de 100 caracteres
        if (empty($apellidos)) {
            $errores["apellidos"][] = "El campo 'apellidos' es obligatorio.";
        }
        if (strlen($apellidos) > 100){
            $errores["apellidos"][] = "El campo 'apellidos' es obligatorio y no puede exceder los 100 caracteres.";
        }
        // Validar `contrasena`: No vacío y máximo de 100 caracteres
        if (empty($contrasena)) {
            $errores["contrasena"][] = "El campo 'contraseña' es obligatorio.";
        }
        if(strlen($contrasena) > 100) {
            $errores["contrasena"][] = "No puede exceder los 100 caracteres.";
        }
        //Filtrar array de errores para eliminar claves vacias
        $errores = array_filter($errores);
        //Si hay errores, devolverlos
        if(!empty($errores))
        {
            return ["success" => false, "errores" => $errores];
        }
        return ["success" => true];
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
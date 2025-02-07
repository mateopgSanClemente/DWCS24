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
     * @param int    $rol        Rol del usuario (obligatorio, 0 para usuario o 1 para administrador).
     * @param string $contrasena Contraseña del usuario (obligatorio, máx. 100 caracteres).
     *
     * @return array Retorna un array asociativo con la siguiente información:
     *                      - "success" (bool): true si la validación es exitosa, false en caso contrario.
     *                      - "errores"? (string): Si hay errores, incluyendo un array asociativo "errores".
     * 
     */
    function validar_usuario (string $username, string $nombre, string $apellidos, int $rol, string $contrasena) : array {
        $errores = [
            "username" => [],
            "nombre" => [],
            "apellidos" => [],
            "contrasena" => [],
            "rol" => []
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
        // Validar rol: Obligatorio, debe ser un número entero y solo puede contener los valores 0 y 1.
        if (!isset($rol)) {
            $errores["rol"][] = "El campo 'rol' es obligatorio.";
        }
        if (!is_int($rol)) {
            $errores["rol"][] = "El campo 'rol' debe ser un entero.";
        } else if (($rol !== 0) && ($rol !== 1)) {
            $errores["rol"][] = "El campo 'rol' debe contener el valor 0 para 'usuario' y 1 para 'administrador'.";
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

/**
 * Valida los datos de un usuario.
 *
 * Valida que el username, nombre y apellidos sean obligatorios y cumplan con las longitudes máximas especificadas.
 * La contraseña es opcional: si se proporciona, se valida que no exceda los 100 caracteres.
 *
 * @param string      $username   El nombre de usuario. Obligatorio, máximo 50 caracteres.
 * @param string      $nombre     El nombre del usuario. Obligatorio, máximo 50 caracteres.
 * @param string      $apellidos  Los apellidos del usuario. Obligatorio, máximo 100 caracteres.
 * @param int         $rol        Rol del usuario. Obligatorio.
 * @param string|null $contrasena La contraseña del usuario. Opcional, si se proporciona, no debe exceder 100 caracteres.
 *
 * @return array Retorna un array con la clave 'success' que indica si la validación fue exitosa.
 *               En caso de errores, retorna 'success' => false y un array 'errores' con los mensajes correspondientes.
 */

function validar_modificar_usuario(string $username, string $nombre, string $apellidos, int $rol, ?string $contrasena = null): array {
    $errores = [
        "username"   => [],
        "nombre"     => [],
        "apellidos"  => [],
        "rol"        => [],
        "contrasena" => []
    ];

    // Validar username: Obligatorio y máximo 50 caracteres.
    if (empty($username)) {
        $errores["username"][] = "El campo 'username' es obligatorio.";
    }
    if (strlen($username) > 50) {
        $errores["username"][] = "No puede exceder los 50 caracteres.";
    }

    // Validar nombre: Obligatorio y máximo 50 caracteres.
    if (empty($nombre)) {
        $errores["nombre"][] = "El campo 'nombre' es obligatorio.";
    }
    if (strlen($nombre) > 50) {
        $errores["nombre"][] = "No puede exceder los 50 caracteres.";
    }

    // Validar apellidos: Obligatorio y máximo 100 caracteres.
    if (empty($apellidos)) {
        $errores["apellidos"][] = "El campo 'apellidos' es obligatorio.";
    }
    if (strlen($apellidos) > 100) {
        $errores["apellidos"][] = "No puede exceder los 100 caracteres.";
    }

    // Validar rol: Obligatorio, debe ser un número entero y solo puede contener los valores 0 y 1.
    if (!isset($rol)) {
        $errores["rol"][] = "El campo 'rol' es obligatorio.";
    }
    if (!is_int($rol)) {
        $errores["rol"][] = "El campo 'rol' debe ser un entero.";
    } else if (($rol !== 0) && ($rol !== 1)) {
        $errores["rol"][] = "El campo 'rol' debe contener el valor 0 para 'usuario' y 1 para 'administrador'.";
    }

    // Validar contraseña: Opcional, pero si se proporciona, no debe exceder 100 caracteres.
    if (!empty($contrasena) && strlen($contrasena) > 100) {
        $errores["contrasena"][] = "No puede exceder los 100 caracteres.";
    }

    // Filtrar el array de errores para eliminar claves sin mensajes (claves vacías)
    $errores = array_filter($errores);

    if (!empty($errores)) {
        return ["success" => false, "errores" => $errores];
    }
    return ["success" => true];
}
    /**
     * Valida los datos de una tarea antes de ser almacenada en la base de datos.
     *
     * @param string $titulo      Título de la tarea (obligatorio, máx. 50 caracteres).
     * @param string $descripcion Descripción de la tarea (opcional, máx. 250 caracteres).
     * @param string $estado      Estado de la tarea (obligatorio: 'Pendiente', 'En proceso' o 'Completada').
     * @param mixed   $id_usuario  ID del usuario asociado (obligatorio, número entero).
     *
     * @return array Retorna un array con la clave con la siguiente información:
     *                      - 'success' (bool): true si todos los campos son correctos, false en caso contrario.
     *                      - 'errores'? (array): Array asociativo con los errores de cada campo
     */
    function validar_tarea(string $titulo, string $descripcion,  string $estado,  int | string $id_usuario): array {
        // Inicializar array de errores
        $errores = [
            "titulo" => [],
            "descripcion" => [],
            "estado" => [],
            "id_usuario" => []
        ];
        // Validar título
        if (empty($titulo)) {
            $errores["titulo"][] = "El título es obligatorio.";
        } else if (strlen($titulo) > 50) {
            $errores["titulo"][] = "No debe exceder los 50 caracteres.";
        }
        // Validar descripción (puede ser nula)
        if (strlen($descripcion) > 250) {
            $errores["descripcion"][] = "La descripción no debe exceder los 250 caracteres.";
        }
        // Validar estado
        $estados_validos = ['Pendiente', 'En proceso', 'Completada'];
        if (empty($estado)) {
            $errores["estado"][] = "El estado es obligatorio.";
        } elseif (!in_array($estado, $estados_validos)) {
            $errores["estado"][] = "El estado debe ser uno de los siguientes: " . implode(', ', $estados_validos) . ".";
        }
        // Validar id_usuario, al seleccionar la columna de un campo AUTO_INCREMENT el valor nunca puede ser 0. ¿?
        if (empty($id_usuario)) {
            $errores["id_usuario"][] = "El ID del usuario es obligatorio.";
        }
        // Filtrar errores vacíos
        $errores = array_filter($errores);
        // Si hay errores, devolverlos
        if (!empty($errores)) {
            return ["success" => false, "errores" => $errores];
        }
        return ["success" => true];
    }
?>
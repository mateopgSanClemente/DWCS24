<?php
    session_start();
    /**
     * TODO:
     *  - Validar y sanitizar los datos recividos desde el furmulario
     *  - (Opcional): Evitar revelar cual fue el motivo del error mediante un error generico para evitar ataques de enumeración de usuario (investigar).
     *  - (Opcional): Registrar intentos fallidos de inicio de sesión para poder detectar patrones de ataque o abusos, podría hacerlo desde la base de datos en un fichero de log (investigar).
     */
    require_once "pdo.php";
    require_once "utils.php";
    /**
     * Datos formulario login, sanear mediantela mi función test_input
     * Es necesario validar y sanear los datos introducidos en el fomrulario (investigar)
     * @link Función filter_input: https://www.php.net/manual/es/function.filter-input.php
     * @link Función filter_var: https://www.php.net/manual/es/function.filter-var.php
     * @link Tipos de filtro: https://www.php.net/manual/es/filter.filters.validate.php
     */
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);
    // Conectar con la base de datos
    $resultado_conectar_PDO = conectar_PDO();
    // Comprobar conexión
    if(!$resultado_conectar_PDO["success"]){
        header ("Location: login.php?error=conexion");
        exit;
    } else {
        // Guardar usuario en la base de datos
        $conexion_PDO = $resultado_conectar_PDO["conexion"];
        // Crear una instancia e la clase Usuario con la propiedad username.
        $usuario = new Usuarios($username);
        // Buscar usuario en la base de datos
        $resultado_buscar_usuario = seleccionar_usuario_pass_rol($conexion_PDO, $usuario);
        // Comprobar el resultado
        if (!$resultado_buscar_usuario["success"]){
            // Cierro sesión
            $conexion_PDO = null;
            header ("Location: login.php?error=usuario");
            exit;
        } else {
            // Recoger el valor de la contraseña
            $usuario = $resultado_buscar_usuario["usuario"];
            $password_usuario = $usuario->getContrasena();
            $rol_usuario = $usuario->getRol();
            // Comprobar que la contraseña coincide para el usuario
            if (!password_verify($password, $password_usuario)){
                // Si no coincide, redirecciono a login y muestro el problema
                // Cierro sesión
                $conexion_PDO = null;
                header("Location: login.php?error=pass");
                exit;
            } else {
                // Regeneramos el id de sesion despues de verificar las credenciales y antes de dar valores a la variables de sesión.
                session_regenerate_id(true);
                // Si coincide, guardamos el usuario y su rol en una variable de sesion
                $_SESSION["usuario"] = $username;
                $_SESSION["rol"] = $rol_usuario;
                // Cierro sesión
                $conexion_PDO = null;
                header ("Location: index.php");
                exit;
            }
        }
    }
    
?>
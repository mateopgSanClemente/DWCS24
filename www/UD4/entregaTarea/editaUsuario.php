<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
    // Redirigir a index en caso de que la persona que pretende acceder lo haga sin sen administrador
    if ($_SESSION["rol"] !== 1) {
        header("Location: index.php");
        exit;
    }
?>
<?php include_once "head.php";?>
    <body>
        <!-- header -->
        <?php include_once "header.php";?>
        <div class="container-fluid d-flex flex-column">
            <div class="row">
                <!-- menu -->
                <?php include_once "menu.php";?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado de la modificación de usuario</h2>
                    <?php
                        /**
                         *  TODO:
                         *  - Modificar el código para que incluya el campo rol
                         */
                        require_once "utils.php";
                        // Guardar ID usuario en una variable
                        $id_usuario = $_GET['id'];
                        // Recuperar los datos enviados a través del formulario
                        $usuario_username_nuevo = $_POST["username"];
                        $usuario_nombre_nuevo = $_POST["nombre"];
                        $usuario_apellidos_nuevo = $_POST["apellidos"];
                        // Comprobar que la contraseña está definida, en caso contraro devolver null
                        $usuario_contrasena_nuevo = isset($_POST["contrasena"]) ? $_POST["contrasena"] : null;
                        // Incluir el rol y convertirlo en entero.
                        $usuario_rol_nuevo = intval($_POST["rol"]);
                        // Validar los datos, si no son válidos, mostrar mensaje de error, todos son obligatorios menos la contraseña
                        $resultado_validar_usuario = validar_modificar_usuario($usuario_username_nuevo, $usuario_nombre_nuevo, $usuario_apellidos_nuevo, $usuario_rol_nuevo, $usuario_contrasena_nuevo);
                        //Comprobar los resultados, aunque pienso que sería más conveniente hacerlo en la página del propio formulario
                        if (!$resultado_validar_usuario["success"]){
                            // Crear una lista dinámica de mensajes con información sobre los errores asociados a un campo.
                            foreach ($resultado_validar_usuario["errores"] as $nombre_campo => $errores) {
                                echo "<h4 class='pt-2 pb-4 mb-3 border-bottom'>Campo <b>$nombre_campo</b></h4>";
                                echo "<ul>";
                                foreach ($errores as $error) {                                
                                    echo "<li class='alert alert-warning' role='alert'>" . $error . "</li>";                                     
                                }
                                echo "</ul>";
                            }               
                        } else {
                            // Si los resultados son válidos, sanearlos. No tengo que sanear el valor del rol, la función validar_modificar_usuario controla que su valor sea el correcto.
                            $usuario_username_nuevo = test_input($usuario_username_nuevo);
                            $usuario_nombre_nuevo = test_input($usuario_nombre_nuevo);
                            $usuario_apellidos_nuevo = test_input($usuario_apellidos_nuevo);
                            $usuario_contrasena_nuevo = test_input($usuario_contrasena_nuevo);
                            // Crear una conexión PDO
                            require_once "pdo.php";
                            $resultado_conexión_PDO = conectar_PDO();
                            // Comprobar la conexión
                            if (!$resultado_conexión_PDO["success"]){
                                echo "<div class='alert alert-danger'>" . $resultado_conexión_PDO["mensaje"] . "</div>";
                            } else {
                                // Guardar conexión PDO en una variable
                                $conexion_PDO = $resultado_conexión_PDO["conexion"];
                                // Modificar usuario
                                $resultado_modificar_usuario = modificar_usuario($conexion_PDO, $id_usuario, $usuario_username_nuevo, $usuario_nombre_nuevo, $usuario_apellidos_nuevo, $usuario_rol_nuevo, $usuario_contrasena_nuevo);
                                //Mostrar mensaje con los resultados de la modificación
                                if (!$resultado_modificar_usuario["success"]){
                                    echo "<div class='alert alert-warning'>" . $resultado_modificar_usuario["mensaje"] . "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>" . $resultado_modificar_usuario["mensaje"] . "</div>";
                                }
                                // Cerrar conexión
                                $conexion_PDO = null;
                            }
                        }
                    ?>
                </main>
            </div>
        </div>
        <?php include_once "footer.php";?>
    </body>
</html>
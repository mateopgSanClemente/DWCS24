<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
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
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado del registro de usuario</h2>
                    <?php
                        require_once "utils.php";
                        //Recoger los resultados en variables
                        $username = $_POST["username"];
                        $nombre = $_POST["nombre"];
                        $apellidos = $_POST["apellidos"];
                        $contrasena = $_POST["contrasena"];
                        // Convertir el tipo de dato a entero
                        $rol = intval($_POST["rol"]);
                        //Comprobar errores
                        $resultado_validar = validar_usuario($username, $nombre, $apellidos, $rol, $contrasena);
                        //Comprobar los resultados, aunque pienso que sería más conveniente hacerlo en la página del propio formulario
                        if (!$resultado_validar["success"]){
                            // Creare una lista dinámica de mensajes con información sobre los errores asociados a un campo.
                            foreach ($resultado_validar["errores"] as $nombre_campo => $errores) {
                                echo "<h4 class='pt-2 pb-4 mb-3 border-bottom'>Campo <b>$nombre_campo</b></h4>";
                                echo "<ul>";
                                foreach ($errores as $error) {                                
                                    echo "<li class='alert alert-warning' role='alert'>" . $error . "</li>";                                     
                                }
                                echo "</ul>";
                            }               
                        } else {
                            require_once "pdo.php";
                            //Filtrar los resultados
                            $username = test_input($username);
                            $nombre = test_input($nombre);
                            $apellidos = test_input($apellidos);
                            $contrasena = test_input($contrasena);
                            //Crear conexión con la base de datos
                            $resultado_conexion_PDO = conectar_PDO();
                            // Variable que guarda la instancia PDO
                            $conexion_PDO = $resultado_conexion_PDO["conexion"];
                            if(!$resultado_conexion_PDO["success"]) {
                                echo "<div class='alert alert-danger'>" . $resultado_conexion_PDO["mensaje"] . "</div>";
                            } else {
                                // Insertar los datos en la tabla usuarios
                                $resultado_agregar_usuario = agregar_usuario($conexion_PDO, $username, $nombre, $apellidos, $contrasena, $rol);
                                // Comprobar que el usuario se agrego correctamente
                                if(!$resultado_agregar_usuario["success"]) {
                                    echo ("<div class='alert alert-warning'>" . $resultado_agregar_usuario["mensaje"] . "</div>");
                                }
                                else {
                                    echo ("<div class='alert alert-success'>" . $resultado_agregar_usuario["mensaje"] . "</div>");
                                }
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
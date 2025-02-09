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
<?php include_once "head.php"; ?>
    <body>
        <!-- header -->
        <?php include_once "header.php";?>
          
        <div class='container-fluid d-flex flex-column'>
            <div class="row">
                <?php include_once "menu.php";?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Modificar Usuario</h2>
                    <?php
                        // Muestro los errores en la validación del formulario en caso de que existan
                        if (isset($_SESSION["errorVal"])){
                            // Crear una lista dinámica de mensajes con información sobre los errores asociados a un campo.
                            foreach ($_SESSION["errorVal"] as $nombre_campo => $errores) {
                                echo "<h4 class='pt-2 pb-4 mb-3 border-bottom'>Campo <b>$nombre_campo</b></h4>";
                                echo "<ul>";
                                foreach ($errores as $error) {                                
                                    echo "<li class='alert alert-warning' role='alert'>" . $error . "</li>";                                     
                                }
                                echo "</ul>";
                            }
                            unset($_SESSION["errorVal"]);
                        } else if (isset($_SESSION["errorConPDO"])){
                            echo "<div class='alert alert-danger'>" . $_SESSION["errorConPDO"] . "</div>";
                            unset($_SESSION["errorConPDO"]);
                        } else if (isset($_SESSION["errorInsUser"])){
                            echo "<div class='alert alert-warning'>" . $_SESSION["errorInsUser"] . "</div>";
                            unset($_SESSION["errorInsUser"]);
                        } else if (isset($_SESSION["success"])){
                            echo "<div class='alert alert-success'>" . $_SESSION["success"] . "</div>";
                            unset($_SESSION["success"]);
                        }
                        // Crear conexión PDO
                        require_once "pdo.php";
                        $resultado_conexion_PDO = conectar_PDO();
                        // Comprobar conexión
                        if (!$resultado_conexion_PDO["success"]){
                            echo "<div class='alert alert-danger' role='alert'>" . $resultado_conexion_PDO["mensaje"] . "</div>";
                        } else {
                            // Guardar conexión en una variable
                            $conexion_PDO = $resultado_conexion_PDO["conexion"];
                            //Guardar el id de usuario en una variable
                            $id_usuario = $_GET['id'];
                            // Seleccionar usuario a editar
                            $resultado_usuario = seleccionar_usuario_id($conexion_PDO, $id_usuario);
                            // Comprobar que se selecciono el usuario
                            if (!$resultado_usuario["success"]){
                                echo "<div class='alert alert-warning' role='alert'>" . $resultado_usuario["mensaje"] . "</div>";
                            } else {
                                //Recuperados los datos los guardo en variables, la función ya los descodifica.
                                $datos_usuario = $resultado_usuario["datos"];
                                $username = $datos_usuario["username"];
                                $nombre = $datos_usuario["nombre"];
                                $apellidos = $datos_usuario["apellidos"];
                                $rol = intval($datos_usuario["rol"]);
                            }
                            // Cerrar conexión
                            $conexion_PDO = null;
                        }
                    ?>
                    <section>
                        <form class="mb-5" action="editaUsuario.php?id=<?php echo $id_usuario; ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" value="<?php echo $username; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" id="apellidos" value="<?php echo $apellidos; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="text" class="form-control" name="contrasena" id="apellidos" placeholder="Nueva contraseña del usuario">
                            </div>
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select class="form-select" name="rol" id="rol">
                                    <option value="0" <?php echo ($rol === 0) ? "selected" : ""; ?>>Usuario</option>
                                    <option value="1" <?php echo ($rol === 1) ? "selected" : ""; ?>>Administrador</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success mb-3">Registrar</button>
                        </form>
                    </section>
                </main>
            </div>
        </div>

        <!-- footer -->
        <?php include_once "footer.php";?>
    </body>
</html>
<?php include_once("head.php"); ?>
    <body>
        <!-- header -->
        <?php include_once("header.php");?>
          
        <div class='container-fluid d-flex flex-column'>
            <div class="row">
                <?php include_once("menu.php");?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Modificar Usuario</h2>
                    <?php
                        require_once("pdo.php");
                        //Guardar el id de usuario en una variable
                        $id = $_GET['id'];

                        //Crear conexión con la base de datos
                        list($conexion, $mensaje_estado_conexion) = conectar_PDO();

                        if($conexion === false)
                        {
                            echo "<div class='alert alert-warning>" . $mensaje_estado_conexion . "</div>";
                        }
                        else
                        {
                            //Seleccionar el usuario
                            list($comprobacion, $resultado_consulta) = seleccionar_usuario_id($conexion, $id);

                            if(!$comprobacion)
                            {
                                echo $resultado_consulta;
                            }
                            else
                            {
                                //Recuperados los datos los guardo en variables y los descodifica
                                $username = htmlspecialchars_decode($resultado_consulta["username"]);
                                $nombre = htmlspecialchars_decode($resultado_consulta["nombre"]);
                                $apellidos = htmlspecialchars_decode($resultado_consulta["apellidos"]);
                            }
                        }

                    ?>
                    <section>
                        <form action="nuevoUsuario.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="<?php echo $username; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="<?php echo $nombre; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="<?php echo $apellidos; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="text" class="form-control" name="contrasena" id="apellidos" placeholder="Nueva contraseña del usuario" required>
                            </div>
                            <button type="submit" class="btn btn-success mb-3">Registrar</button>
                        </form>
                    </section>
                </main>
            </div>
        </div>

        <!-- footer -->
        <?php include_once("footer.php");?>
    </body>
</html>
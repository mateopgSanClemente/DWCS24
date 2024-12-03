<?php include_once ("head.php");?>
    <body>
        <!-- header -->
        <?php include_once("header.php");?>
        <div class="container-fluid d-flex flex-column">
            <div class="row">
                <!-- menu -->
                <?php include_once("menu.php");?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado del registro de usuario</h2>
                    <?php
                    try
                    {
                        require_once("utils.php");
                        //Recoger los resultados en variables
                        $username = $_POST["username"];
                        $nombre = $_POST["nombre"];
                        $apellidos = $_POST["apellidos"];
                        $contrasena = $_POST["contrasena"];

                        //Comprobar errores
                        $error = false;
                        $mensaje_error = [];
                        //Validar los resultados
                        //TODO: Validar correctamente los usuarios!! Los mensajes no se imprimen correctamente
                        list($error, $mensaje_error) = validar_usuario($username, $nombre, $apellidos, $contrasena);

                        if($error)
                        {
                            echo "<div class='alert alert-warning'>" . $mensaje_error . "</div>";
                        }
                        else
                        {
                            require_once("pdo.php");
                            //Filtrar los resultados
                            $username = test_input($username);
                            $nombre = test_input($nombre);
                            $apellidos = test_input($apellidos);
                            $contrasena = test_input($contrasena);

                            //Insertar los resultados en la tabla usuarios

                            //Crear conexión con la base de datos
                            list($conexion, $mensaje_estado_conexion) = conectar_PDO();

                            if($conexion === false)
                            {
                                echo "<div class='alert alert-warning>" . $mensaje_estado_conexion . "</div>";
                            }
                            else
                            {
                                //Realizar la consulta y comprobar que esta se realizo correctamente.
                                list($comprobacion, $resultado) = agregar_usuario($conexion, $username, $nombre, $apellidos, $contrasena);

                                if($comprobacion)
                                {
                                    echo ("<div class='alert alert-success'>" . $resultado . "</div>");
                                }
                                else
                                {
                                    echo ("<div class='alert alert-warning'>" . $resultado . "</div>");
                                }
                            }
                        }
                    }
                    catch (PDOException $e)
                    {
                        echo "<div class='alert alert-warning'>Error: " . $e . "</div>";
                    }
                    finally
                    {
                        $conexion = null;
                    }
                    ?>
                </main>
            </div>
        </div>
        <?php include_once("footer.php");?>
    </body>
</html>
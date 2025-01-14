<?php include_once ("head.php");?>
    <body>
        <!-- header -->
        <?php include_once("header.php");?>
        <div class="container-fluid d-flex flex-column">
            <div class="row">
                <!-- menu -->
                <?php include_once("menu.php");?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado de la modificación de usuario</h2>
                    <?php
                        require_once("utils.php");

                        require_once ("pdo.php");
                        
                        list($conexion, $mensaje_estado_conexion) = conectar_PDO();

                        if($conexion === false)
                        {
                            echo "<div class='alert alert-warning>" . $mensaje_estado_conexion . "</div>";
                        }
                        else
                        {
                            $id = $_GET['id'];
                            list($comprobacion, $resultado) = seleccionar_usuario_id($conexion, $id);

                            if(!$comprobacion)
                            {
                                echo ("<div class='alert alert-warning' role='alert'>" . $resultado . "</div>");
                            }
                            else
                            {
                                //Recuperar los datos originales del usuario
                                $usuario_original = [
                                    'username' => htmlspecialchars_decode($resultado['username']),
                                    'nombre' => htmlspecialchars_decode($resultado['nombre']),
                                    'apellidos' => htmlspecialchars_decode($resultado['apellidos']),
                                    'contrasena' => htmlspecialchars_decode($resultado['contrasena'])
                                ];

                                //Comprobar errores
                                $error = false;
                                $mensaje_error = [];

                                //Validar los resultados
                                if(!empty($username) && !validar_usuario($username))
                                {
                                    $error = true;
                                    $mensaje_error[] = "El campo 'Username' debe contener un máximo de 50 caracteres.";
                                }

                                if(!empty($nombre) && !validar_usuario($nombre))
                                {
                                    $error = true;
                                    $mensaje_error[] = "El campo 'Nombre' debe contener un máximo de 50 caracteres.";
                                }
                                if(!empty($apellidos) && !validar_usuario($apellidos))
                                {
                                    $error = true;
                                    $mensaje_error[] = "El campo 'Apellidos' debe contener un máximo de 100 caracteres.";
                                }

                                //Compruebo que el campo contraseña no esté vacío antes de validarlo porque quiero que se conserve la orginal en caso de que esté vacía.
                                if(!empty($contrasena) && !validar_usuario($contrasena))
                                {
                                    $error = true;
                                    $mensaje_error[] = "El campo 'Contraseña' debe contener un máximo de 100 caracteres.";
                                }
                                //En caso de que no se den errores
                                if(!$error)
                                {
                                    //Recoger los resultados en variables
                                    //TODO: CREO QUE NO HACE FALTA
                                    $username = $_POST["username"];
                                    $nombre = $_POST["nombre"];
                                    $apellidos = $_POST["apellidos"];
                                    $contrasena = $_POST["contrasena"];
                                    //Filtrar los resultados
                                    $username = test_input($username);
                                    $nombre = test_input($nombre);
                                    $apellidos = test_input($apellidos);
                                    $contrasena = test_input($contrasena);

                                    //Si lo campos introducidos están vacíos se compruebas los valores originales
                                    //TODO: tiene que haber forma de hacer esto mediante un bucle
                                    if (empty($username))
                                    {
                                        $username = $usuario_original['username'];
                                    }
                                    if (empty($nombre))
                                    {
                                        $nombre = $usuario_original['nombre'];
                                    }
                                    if (empty($apellidos))
                                    {
                                        $apellidos = $usuario_original['apellidos'];
                                    }
                                    if (empty($contrasena))
                                    {
                                        $contrasena = $usuario_original['contrasena'];
                                    }

                                    //Realizar la consulta y comprobar que esta se realizo correctamente.
                                    list($comprobacion, $resultado) = modificar_usuario($conexion, $id, $username, $nombre, $apellidos, $contrasena);
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
                    ?>
                </main>
            </div>
        </div>
        <?php include_once("footer.php");?>
    </body>
</html>
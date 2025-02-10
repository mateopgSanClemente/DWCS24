<?php include_once('head.php'); ?>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col">
                
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Actualizar usuario</h2>
                </div>

                <div class="container justify-content-between">
                    <form action="editar.php" method="POST" class="mb-2 w-50">
                        <?php
                        require_once('database.php');
                        if (!empty($_POST))
                        {
                            require_once('utils.php');
                            $nombre = filtraCampo($_POST['nombre']);
                            $apellidos = filtraCampo($_POST['apellidos']);
                            $edad = esNumeroValido($_POST['edad']) ? $_POST['edad'] : -1;
                            $provincia = filtraCampo($_POST['provincia']);

                            require_once('Usuario.php');

                            $usuario = new Usuario($nombre, $apellidos, $edad, $provincia);
                            $usuario->setId($_POST['id']);

                            $errores = $usuario->validar();

                            if (!empty($errores))
                            {
                                foreach ($errores as $error) {
                                    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error) . '</div>';
                                }
                            }
                            else
                            {
                                require_once('database.php');
                                $resultado = actualizaUsuario($usuario);
                                if ($resultado[0])
                                {
                                    echo '<div class="alert alert-success" role="alert">Usuario actualizado correctamente.</div>';
                                }
                                else
                                {
                                    echo '<div class="alert alert-danger" role="alert">Ocurrió un error actualizando el usuario: ' . $resultado[1] . '</div>';
                                }
                            }
                        }
                        else
                        {
                            echo '<div class="alert alert-danger" role="alert">Debes acceder a través del formulario de edición de usuarios.</div>';
                        }
                        ?>
                        
                    </form>
                </div>

                <div class="container justify-content-between mb-2">
                    <a class="btn btn-info btn-sm" href="listaUsuarios.php" role="button">Volver</a>
                </div>

            </main>
        </div>
    </div>
    
    <?php include_once('footer.php'); ?>
    
</body>
</html>

<?php include_once('head.php'); ?>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col">
                
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Nuevo usuario</h2>
                </div>

                <div class="container justify-content-between">
                    <?php
                    require_once('utils.php');
                    $nombre = filtraCampo($_POST['nombre']);
                    $apellidos = filtraCampo($_POST['apellidos']);
                    $edad = esNumeroValido($_POST['edad']) ? $_POST['edad'] : -1;
                    $provincia = filtraCampo($_POST['provincia']);

                    require_once('Usuario.php');

                    $usuario = new Usuario($nombre, $apellidos, $edad, $provincia);

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
                        $resultado = nuevoUsuario($usuario);
                        if ($resultado[0])
                        {
                            echo '<div class="alert alert-success" role="alert">Usuario guardado correctamente.</div>';
                        }
                        else
                        {
                            echo '<div class="alert alert-danger" role="alert">Ocurrió un error guardando el usuario: ' . $resultado[1] . '</div>';
                        }
                    }
                    ?>
                </div>

                <?php include_once('back.php'); ?>

            </main>
        </div>
    </div>
    
    <?php include_once('footer.php'); ?>
    
</body>
</html>

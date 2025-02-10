<!DOCTYPE html>
<html lang="es">
<?php
    include_once "head.php";
?>
<body>

<!-- Header -->
<?php
    include_once "header.php";
?>

<!-- Layout -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php
            include_once "nav_menu.php";
        ?>

        <!-- Main Content -->
        <main class="col-md-9 main-content">
            <h2>Estado del registro</h2>
            
                <?php
                    //Recoger valores tomados del formulario y asignarlo a una variable
                    $nombre = $_POST["nombre"];
                    $apellidos = $_POST["apellidos"];
                    $edad = $_POST["edad"];
                    $provincia = $_POST["provincia"];

                    //Validar los datos
                    require_once ("utils_validar.php");
                    $error = false;
                    $mensaje_error = [];
                    //Verificar nombre
                    if (!comprobar_campo($nombre))
                    {
                        $error = true;
                        $mensaje_error[] = "El campo 'nombre' no puede estar vacio y debe contener por lo menos un caracter alfabético.";
                    }
                    //Verificar apellidos
                    if (!comprobar_campo($apellidos))
                    {
                        $error = true;
                        $mensaje_error[] = "El campo 'apellidos' no puede estar vacio y debe contener por lo menos un caracter alfabético.";
                    }
                    //Verificar edad
                    if (!comprobar_campo($edad))
                    {
                        $error = true;
                        $mensaje_error[] = "El campo 'edad' no puede estar vacío y debe ser un número que se encuentre entre los valores 18 y 130.";
                    }
                    //Verificar provincia
                    if (!comprobar_campo($provincia))
                    {
                        $error = true;
                        $mensaje_error[] = "El campo 'provincia' no puede estar vacio y debe contener por lo menos un caracter alfabético.";
                    }

                    if (!empty($mensaje_error))
                    {
                        foreach($mensaje_error as $mensaje)
                        {
                            echo '<div class="alert alert-danger" role="alert">' . $mensaje . "</div>";
                        }
                    }
                    if (!$error)
                    {
                        require_once("utils_bases_datos.php");
                        //Crear conexión
                        $conexion = conectar();
                        //Filtrar campos para evitar inyección html
                        $campos_filtrado = insertar_cliente($conexion, test_input($nombre), test_input($apellidos), test_input($edad), test_input($provincia));
                        if($campos_filtrado[0] === true)
                        {
                            echo '<div class="alert alert-success" role="alert">' . $campos_filtrado[1] . '</div>';
                        }
                        else
                        {
                            echo '<div class="alert alert-warning" role="alert">' . $campos_filtrado[1] . '</div>';
                        }
                    }
                ?>
        </main>
    </div>
</div>

<!-- Footer -->
<?php
    include_once "footer.php";
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

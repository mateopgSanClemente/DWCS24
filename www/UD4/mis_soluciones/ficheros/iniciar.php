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
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php
                include_once "nav_menu.php";
            ?>            
            <!-- Layout -->
            <main class="col-md-9 main-content">
                <?php
                    require_once ("utils_bases_datos.php");

                    //Crear base de datos
                    $conexion = conectar("db", "root", "test", null);

                    $creacion_base_datos = crear_base_datos ($conexion);

                    if ($creacion_base_datos[0] === false)
                    {
                        echo '<div class="alert alert-warning" role="alert">' . $creacion_base_datos[1] . '</div>';
                    }
                    else if ($creacion_base_datos[0] === true)
                    {
                        echo '<div class="alert alert-success" role="alert">' . $creacion_base_datos[1] . '</div>';
                    }

                    //Crear tabla 'clientes'
                    $conexion = conectar();
                    
                    $tabla_usuarios = crear_tabla($conexion);

                    if ($tabla_usuarios[0] === true)
                    {
                        echo '<div class="alert alert-success" role="alert">' . $tabla_usuarios[1] . '</div>';
                    }
                    else if ($tabla_usuarios[0] === false)
                    {
                        echo '<div class="alert alert-warning" role="alert">' . $tabla_usuarios[1] . '</div>';
                    }

                    // Crear tabla 'productos'
                    $conexion = conectar();
                    
                    $tabla_productos = crear_tabla_productos($conexion);

                    if ($tabla_productos[0] === true)
                    {
                        echo '<div class="alert alert-success" role="alert">' . $tabla_prodctos[1] . '</div>';
                    }
                    else if ($tabla_productos[0] === false)
                    {
                        echo '<div class="alert alert-warning" role="alert">' . $tabla_productos[1] . '</div>';
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
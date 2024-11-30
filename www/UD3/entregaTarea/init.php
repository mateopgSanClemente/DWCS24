<?php include_once ("head.php"); ?>
<body>
    <!--header-->
    <?php include_once 'header.php';?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!--menu-->
            <?php
                include 'menu.php';
            ?>
            <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado de la base de datos 'tareas' y la tabla 'usuarios'</h2>
                    <?php
                        require_once("utils_base_datos.php");

                        $conexion = conectar("db", "root", "test", null);

                        $resultado_base_datos = crear_base_datos ($conexion);

                        if ($resultado_base_datos[0] === false)
                        {
                            echo "<div class='alert alert-warning' role='alert'>" . $resultado_base_datos[1] . "</div>";
                        }
                        else if ($resultado_base_datos[0] === true)
                        {
                            echo "<div class='alert alert-success' role='alert'>" . $resultado_base_datos[1] . "</div>";
                        }

                        $conexion = conectar();

                        $resultado_tabla_usuarios = crear_tabla($conexion);

                        if ($resultado_tabla_usuarios[0] === true)
                        {
                            echo "<div class='alert alert-success'>" . $resultado_tabla_usuarios[1] . "</div>";
                        }
                        elseif ($resultado_base_datos[0] === false)
                        {
                            echo "<div class='alert alert-warning'>" . $resultado_tabla_usuarios[1] . "</div>";
                        }
                    ?>
            </main>
        </div>

    </div>
    <!-- footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
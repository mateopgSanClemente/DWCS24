<?php include_once ("head.php"); ?>
<body>
    <!--header-->
    <?php include_once ('header.php');?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!--menu-->
            <?php
                include_once ('menu.php');
            ?>
            <main class="col-md-9 col-sm-12 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Conexión</h2>
                    <?php
                        require_once("mysqli.php");
                        //Comprobar conexion
                        try
                        {
                            $conexion = conectar_mysqli("db", "root", "test", null);
                            $resultado_base_datos = crear_base_datos ($conexion);

                            echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>base de datos</b></h3>";

                            if ($resultado_base_datos[0] === false)
                            {
                                echo "<div class='alert alert-warning' role='alert'>" . $resultado_base_datos[1] . "</div>";
                            }
                            else if ($resultado_base_datos[0] === true)
                            {
                                echo "<div class='alert alert-success' role='alert'>" . $resultado_base_datos[1] . "</div>";
                            }
                            
                            //Cierro la conexión
                            cerrar_conexion($conexion);

                            //Abro una nueva conexión que se conecta a la base de datos
                            $conexion = conectar_mysqli();

                            //Creo la tabla usuarios
                            $resultado_tabla_usuarios = crear_tabla_usuario($conexion);
                            
                            echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>tabla usuarios</b></h3>";

                            if ($resultado_tabla_usuarios[0] === true)
                            {
                                echo "<div class='alert alert-success'>" . $resultado_tabla_usuarios[1] . "</div>";
                            }
                            elseif ($resultado_base_datos[0] === false)
                            {
                                echo "<div class='alert alert-warning'>" . $resultado_tabla_usuarios[1] . "</div>";
                            }
                            
                            //Creo la tabla tareas
                            $resultado_tabla_tareas = crear_tabla_tareas($conexion);

                            echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>tabla tareas</b></h3>";

                            if($resultado_tabla_tareas[0] === true)
                            {
                                echo ("<div class='alert alert-success' role='alert'>" . $resultado_tabla_tareas[1] . "</div>");
                            }
                            elseif ($resultado_tabla_tareas[0] === false)
                            {
                                echo ("<div class='alert alert-warning' role='alert'>") . $resultado_tabla_tareas[1] . "</div>";
                            }

                            //Cierro la conexión
                            cerrar_conexion($conexion);
                        }
                        catch(Exception $e)
                        {
                            echo "<div class='alert alert-warning' role='alert'>Error: " . $e . "</div>";
                        }
                    ?>
            </main>
        </div>
    </div>
    <!-- footer -->
    <?php include ('footer.php'); ?>
</body>
</html>
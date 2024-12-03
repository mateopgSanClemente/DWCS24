<?php include_once("head.php"); ?>
<body>
    <!--header-->
    <?php include_once 'header.php'; ?>   
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!--menu-->
            <?php include_once 'menu.php'; ?>
            <main class="col-md-9 main-content">
                <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado del registro de tarea</h2>             
                    <?php
                        try
                        {                       
                            require_once 'utils.php';

                            //Recoger los resultados en variables
                            $tarea_titulo = $_POST['titulo'];
                            $tarea_descripcion = $_POST['descripcion'];
                            $tarea_estado = $_POST['estado'];
                            $tarea_id_usuario = $_POST['usuario'];

                            //Comprobación de errores y validacion de resultads
                            list($error, $mensaje_error) = validar_tarea($tarea_titulo, $tarea_descripcion, $tarea_estado, $tarea_id_usuario);
                            if($error)
                            {
                                echo "<div class='alert alert-warning'>" . $mensaje_error . "</div>";
                            }
                            else
                            {
                                require_once("mysqli.php");
                                //Filtrar los resultados
                                $tarea_titulo = test_input($tarea_titulo);
                                $tarea_descripcion = test_input($tarea_descripcion);
                                $tarea_estado = test_input($tarea_estado);
                                $tarea_id_usuario = test_input($tarea_id_usuario);

                                //Insertar los resultados en la tabla tareas

                                //Conexion
                                $mysqli_conn = conectar_mysqli();
                                //Insertar datos
                                list($comprobacion, $mensaje_estado_tarea) = agregar_tarea($mysqli_conn, $tarea_titulo, $tarea_descripcion, $tarea_estado, $tarea_id_usuario);
                                
                                //Resultado insercion tarea
                                if($comprobacion === true)
                                {
                                    echo ("<div class='alert alert-success'>" . $mensaje_estado_tarea . "</div>");
                                }
                                else
                                {
                                    echo ("<div class='alert alert-warning'>" . $mensaje_estado_tarea . "</div>");
                                }
                            }
                        }
                        catch (mysqli_sql_exception $e)
                        {
                            echo "<div class='alert alert-warning'>Error: " . $e . "</div>";
                        }
                        finally
                        {
                            cerrar_conexion($mysqli_conn);
                        }
                    ?>
            </main>
        </div>
    </div>
    
    <!--footer-->
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
?>
<?php include_once "head.php"; ?>
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
                        require_once 'utils.php';
                        //Recoger los resultados en variables
                        $tarea_titulo = $_POST['titulo'];
                        $tarea_descripcion = $_POST['descripcion'];
                        $tarea_estado = isset($_POST['estado']) ? $_POST["estado"] : "";
                        // Convertir el valor del id a un tipo entero
                        $tarea_id_usuario = isset($_POST['usuario']) ? intval($_POST['usuario']) : "";
                        //Comprobación de errores y validacion de resultads
                        $resultado_validar = validar_tarea($tarea_titulo, $tarea_descripcion, $tarea_estado, $tarea_id_usuario);
                        if (!$resultado_validar["success"]){
                            // Creare una lista dinámica de mensajes con información sobre los errores asociados a un campo.
                            foreach ($resultado_validar["errores"] as $nombre_campo => $errores) {
                                echo "<h4 class='pt-2 pb-2 mb-3 border-bottom'>Campo </b>$nombre_campo</b></h4>";
                                echo "<ul>";
                                foreach ($errores as $error) {                                
                                    echo "<li class='alert alert-warning' role='alert'>" . $error . "</li>";                                     
                                }
                                echo "</ul>";
                            }
                        } else {
                            require_once "mysqli.php";
                            //Filtrar los resultados
                            $tarea_titulo = test_input($tarea_titulo);
                            $tarea_descripcion = test_input($tarea_descripcion);
                            $tarea_estado = test_input($tarea_estado);
                            $tarea_id_usuario = test_input($tarea_id_usuario);
                            //Insertar los resultados en la tabla tareas

                            //Conexion mysqli
                            $resultado_conexion_mysqli = conectar_mysqli();
                            $mysqli_conn = $resultado_conexion_mysqli["conexion"];
                            // Comprobar que la conexión se realizó correctamente.
                            if (!$resultado_conexion_mysqli["success"]){
                                echo "<div class='alert alert-danger'>" . $resultado_conexion_mysqli["mensaje"] . "</div>";
                            } else {
                                // Insertar datos
                                $resultado_agregar_tarea = agregar_tarea($mysqli_conn, $tarea_titulo, $tarea_descripcion, $tarea_estado, $tarea_id_usuario);
                                // Mostrar mensaje de error o éxito
                                if (!$resultado_agregar_tarea["success"]){
                                    echo "<div class='alert alert-warning'>" . $resultado_agregar_tarea["mensaje"] . "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>" . $resultado_agregar_tarea["mensaje"] . "</div>";
                                }
                                cerrar_conexion($mysqli_conn);
                            }
                        }
                    ?>
            </main>
        </div>
    </div>
    
    <!--footer-->
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
        exit;
    }
?>
<?php include_once "head.php";?>
    <body>
        <!-- header -->
        <?php include_once "header.php";?>
        <div class="container-fluid d-flex flex-column">
            <div class="row">
                <!-- menu -->
                <?php include_once "menu.php";?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado de la eliminación de la tarea</h2>
                    <?php
                        //Crear conexion
                        require_once "mysqli.php";
                        $resultado_conexion_mysqli = conectar_mysqli();
                        // Comprobar conexión
                        if (!$resultado_conexion_mysqli["success"]){
                            // Mostrar mensaje
                            echo ("<div class='alert alert-danger' role='alert'>" . $resultado_conexion_mysqli["error"] . "</div>");
                        } else {
                            // Guarda conexión en una variable
                            $conexion_mysqli = $resultado_conexion_mysqli["conexion"];
                            $id_tarea = $_GET['id'];
                            // Eliminar tarea
                            $resultado_eliminar_tarea = eliminar_tarea($conexion_mysqli, $id_tarea);
                            // Mostrar el resultado de eliminar la tarea
                            if(!$resultado_eliminar_tarea["success"]){
                                echo ("<div class='alert alert-warning' role='alert'>" . $resultado_eliminar_tarea["mensaje"] . "</div>");
                            } else {
                                echo ("<div class='alert alert-success' role='alert'>" . $resultado_eliminar_tarea["mensaje"] . "</div>");
                            }
                        }
                    ?>
                </main>
            </div>
        </div>
        <?php include_once "footer.php";?>
    </body>
</html>
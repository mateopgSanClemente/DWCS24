<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
    // Redirigir a index en caso de que la persona que pretende acceder lo haga sin sen administrador
    if ($_SESSION["rol"] !== 1) {
        header("Location: index.php");
        exit;
    }
?>
<?php include_once "head.php"; ?>
<body>
    <!--header-->
    <?php include_once 'header.php';?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!--menu-->
            <?php
                include_once 'menu.php';
            ?>
            <main class="col-md-9 col-sm-12 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Conexión</h2>
                    <?php
                        /**
                         * TODO:
                         *  - Y si en lugar de cerrar la conexión en cada if lo cierro en un bloque finally?
                         *  - Sería mejor manejar las excepciones de tipo mysqli_exception dentro de este fichero y no en las funciones?
                         */
                        require_once "mysqli.php";
                        //Comprobar conexion
                                              
                        $resultado_conectar = conectar_mysqli();
                        // Comprobar el estado de la conexión
                        if (!$resultado_conectar["success"]){
                            // Mensaje informativo de error
                            echo "<div class='alert alert-danger' role=alert'>Error en la conexión: " . $resultado_conectar["error"] . "</div>";
                        } else {
                            // Crear base de datos
                            $resultado_base_datos = crear_base_datos ($resultado_conectar["conexion"]);
                            // Comprobar que ocurrió correctamente
                            if (!$resultado_base_datos["success"]){
                                // Mostrar mensaje error
                                echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>base de datos</b></h3>";
                                echo "<div class='alert alert-danger' role='alert'>Error al crear la base de datos: " . $resultado_base_datos["mensaje"] . "</div>";
                                // Cerrar conexión
                                cerrar_conexion($resultado_conectar["conexion"]);
                            } else {
                                // Mostrar mensajse éxito.
                                echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>base de datos</b></h3>";
                                echo "<div class='alert alert-success' role='alert'>" . $resultado_base_datos["mensaje"] . "</div>";
                                // Crear tabla usuarios
                                $resultado_tabla_usuarios = crear_tabla_usuario($resultado_conectar["conexion"]);
                                // Comprobar que se creó correctmente
                                if (!$resultado_tabla_usuarios["success"]) {
                                    //Mostrar mensaje de error
                                    echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>tabla usuarios</b></h3>";
                                    echo "<div class='alert alert-danger' role='alert'>" . $resultado_tabla_usuarios["mensaje"] . "</div>";
                                    cerrar_conexion($resultado_conectar["conexion"]);
                                } else {
                                    // Mostrar mensaje de éxito
                                    echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>tabla usuarios</b></h3>";
                                    echo "<div class='alert alert-success' role='alert'>" . $resultado_tabla_usuarios["mensaje"] . "</div>";
                                    // Crear tabla tareas
                                    $resultado_tabla_tareas = crear_tabla_tareas($resultado_conectar["conexion"]);
                                    // Comprobar que se creó correctamente
                                    if (!$resultado_tabla_tareas["success"]){
                                        // Mostrar mensaje de error
                                        echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>tabla tareas</b></h3>";
                                        echo "<div class='alert alert-danger' role='alert'>" . $resultado_tabla_tareas["mensaje"] . "</div>";                                            
                                        cerrar_conexion($resultado_conectar["conexion"]);
                                    } else {
                                        // Mostrar mensaje de éxito
                                        echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>tabla tareas</b></h3>";
                                        echo "<div class='alert alert-success' role='alert'>" . $resultado_tabla_tareas["mensaje"] . "</div>";
                                        // Crear tabla ficheros
                                        $resultado_tabla_ficheros = crear_tabla_ficheros($resultado_conectar["conexion"]);
                                        // Comprobar que se creó correctamente
                                        if (!$resultado_tabla_ficheros["success"]) {
                                            // Mostrar mensaje de error
                                            echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>tabla tareas</b></h3>";
                                            echo "<div class='alert alert-danger' role='alert'>" . $resultado_tabla_ficheros["mensaje"] . "</div>";                                            
                                            cerrar_conexion($resultado_conectar["conexion"]);
                                        } else {
                                            // Mostrar mensaje de éxito
                                            echo "<h3 class='pt-2 pb-4 mb-3 border-bottom'>Estado de la <b>tabla ficheros</b></h3>";
                                            echo "<div class='alert alert-success' role='alert'>" . $resultado_tabla_ficheros["mensaje"] . "</div>";
                                            cerrar_conexion($resultado_conectar["conexion"]);
                                        }
                                    }
                                }
                            }                        
                        }        
                    ?>
            </main>
        </div>
    </div>
    <!-- footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
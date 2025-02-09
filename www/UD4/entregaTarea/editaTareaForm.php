<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
?>
<?php include_once "head.php"; ?>
<body>
    <!-- header -->
    <?php include_once "header.php"; ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- menu -->
            <?php include_once "menu.php"; ?>
            <main class="col-md-9 main-content">
                <h2 class="border-bottom pt-4 pb-2 mb-3">Modificar Tarea</h2>
                <?php
                    // Mostrar errores
                    if (isset($_SESSION["errorVal"])){
                        foreach ($_SESSION["errorVal"] as $nombre_campo => $errores) {
                            echo "<h4 class='pt-2 pb-2 mb-3 border-bottom'>Campo </b>$nombre_campo</b></h4>";
                            echo "<ul>";
                            foreach ($errores as $error) {                                
                                echo "<li class='alert alert-warning' role='alert'>" . $error . "</li>";                                     
                            }
                            echo "</ul>";
                        }
                        unset($_SESSION["errorVal"]);
                    } else if (isset($_SESSION["errorConMysqli"])){
                        echo "<div class='alert alert-danger'>" . $_SESSION["errorConMysqli"] . "</div>";
                        unset($_SESSION["errorConMysqli"]);
                    } else if (isset($_SESSION["errorInsTask"])){
                        echo "<div class='alert alert-warning'>" . $_SESSION["errorInsTask"] . "</div>";
                        unset($_SESSION["errorInsTask"]);
                    } else if (isset($_SESSION["success"])){
                        echo "<div class='alert alert-success'>" . $_SESSION["success"] . "</div>";
                        unset($_SESSION["success"]);
                    }

                    require_once "mysqli.php";
                    //Guardar el id de la tarea
                    $id_tarea = $_GET['id'];
                    //Crear conexión
                    $resultado_conexion_mysqli = conectar_mysqli();
                    //Comprobar estado de la conexión
                    if(!$resultado_conexion_mysqli["success"]){
                        // Mostrar mensaje
                        echo "<div class='alert alert-danger' role='alert'>" . $resultado_conexion_mysqli["mensaje"] . "</div>";
                    } else {
                        // Guardar conexión en variable
                        $conexion_mysqli = $resultado_conexion_mysqli["conexion"];
                        //Seleccionar tarea a modificar
                        $resultado_seleccionar_tarea = seleccionar_tarea_id($conexion_mysqli, $id_tarea);
                        $datos_tarea = $resultado_seleccionar_tarea["resultado"];
                        $tarea_titulo = htmlspecialchars_decode($datos_tarea['titulo']);
                        $tarea_descripcion = htmlspecialchars_decode($datos_tarea['descripcion']);
                        $tarea_estado = htmlspecialchars_decode($datos_tarea['estado']);
                        $tarea_username_usuario = htmlspecialchars_decode($datos_tarea['username']);
                        // Cerrar conexión
                        cerrar_conexion($conexion_mysqli);
                    }
                ?>
                <section>
                <form class="mb-5" action="editaTarea.php?id=<?php echo $id_tarea?>" method="post">
                        <div class="mb-3">
                            <label class="form-label" for="titulo">Titulo</label>
                            <!--Podría acotar el rango de números válidos para este campo-->
                            <input class="form-control" type="text" name="titulo" id="titulo" value="<?php echo $tarea_titulo?>" required>
                        </div>
                        <div class="mb-3">
                            <!-- Podría poner un mínimo y un máximo de caracteres para este campo -->
                            <label class="form-label" for="descripcion">Descripcion</label>
                            <input class="form-control" type="text" name="descripcion" id="descripcion" value="<?php echo $tarea_descripcion?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="estado">Estado</label>
                            <select class="form-select" name="estado" id="estado">
                                <option value="Pendiente" <?php echo $tarea_estado === "Pendiente" ? "selected" : "";?>>Pendiente</option>
                                <option value="En proceso" <?php echo $tarea_estado === "En proceso" ? "selected" : "";?>>En proceso</option>
                                <option value="Completada" <?php echo $tarea_estado === "Completada" ? "selected" : "";?>>Completada</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="usuario">Seleccionar usuario</label>
                                <!-- Generar las opciones de select de forma dinámica -->
                                <?php
                                //Generar conexión
                                require_once "pdo.php";

                                $resultado_conexion_PDO = conectar_PDO();
                                if(!$resultado_conexion_PDO["success"]){
                                    echo "<div class='alert alert-danger'>" . $resultado_conexion_PDO["mensaje"] . "</div>";
                                } else {   
                                    //Sería más eficiente usar una función que sólo selecciones el campo 'username' de la tabla usuarios.
                                    $conexion_PDO = $resultado_conexion_PDO["conexion"];
                                    // Seleccionar usuario
                                    $resultado_seleccionar_usuarios = seleccionar_usuarios($conexion_PDO);
                                    if(!$resultado_seleccionar_usuarios["success"]){
                                        echo "<div class='alert alert-warning'>" . $resultado_seleccionar_usuarios["mensaje"] . "</div>"; 
                                    } else {   
                                        echo "<select class='form-select' name='usuario' id='usuario'>";
                                        foreach ($resultado_seleccionar_usuarios["datos"] as $usuario) {
                                            $selected = ($tarea_username_usuario === $usuario['username']) ? "selected='selected'" : "";
                                            //TODO: Conseguir que imprima selected en lugar de 'selected=""'.
                                            echo "<option value='" . $usuario['id'] . "' $selected>" . $usuario['username'] . "</option>";
                                        }                                        
                                        echo "</select>";
                                    }
                                    // Cerrar conexión PDO
                                    $conexion_PDO = null;
                                }
                            ?>
                        </div>
                        <button type="submit" class="btn btn-success mb-3">Modificar</button>
                    </form>
                </section>
            </main>
        </div>
    </div>
    <!-- footer -->
    <?php include_once "footer.php"; ?>
</body>
 
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
    <?php include 'header.php'; ?>
    
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!--menu-->
            <?php include 'menu.php'; ?>
            <main class="col-md-9 main-content">
            <!--Formulario-->
            <!--Mi idea inicial era añadir el atributo required a algunos de los campos del formulario, pero lo eliminé con el fin de validarlos a través del fichero nueva.php y las funciones del fichero utils.php-->
                <h2 class="pt-4 pb-2 mb-3 border-bottom">Registrar Tarea</h2>
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
                ?>
                <section>
                    <form class="mb-5" action="nueva.php" method="post">
                        <div class="mb-3">
                            <label class="form-label" for="titulo">Titulo</label>
                            <!--Podría acotar el rango de números válidos para este campo-->
                            <input class="form-control" type="text" name="titulo" id="titulo" placeholder="Título de la tarea">
                        </div>
                        <div class="mb-3">
                            <!-- Podría poner un mínimo y un máximo de caracteres para este campo -->
                            <label class="form-label" for="descripcion">Descripcion</label>
                            <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="Descripción de la tarea">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="estado">Estado</label>
                            <select class="form-select" name="estado" id="estado">
                                <option value="" selected disabled>Selecciona un estado</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En proceso">En proceso</option>
                                <option value="Completada">Completada</option>
                            </select>
                        </div>
                        <!-- Seleccionar usuario: se verá el username pero se vinculará a su id -->
                        <div class="mb-3">
                                <!-- Generar las opciones de select de forma dinámica -->
                                <?php
                                    require_once "pdo.php";
                                    //Crear conexión con la base de datos
                                    $resultado_conexion_PDO = conectar_PDO();
                                    // Variable que guarda la instancia PDO
                                    $conexion_PDO = $resultado_conexion_PDO["conexion"];
                                    // Comprobar que la conexión se estableción sin problemas
                                    if(!$resultado_conexion_PDO["success"]) {
                                        echo "<div class='alert alert-danger'>" . $resultado_conexion_PDO["mensaje"] . "</div>";
                                    } else {   
                                        // Seleccionar usuario de la base de datos
                                        // El campo usuario viene predefinido para usuarios normales, que son ellos mismos
                                        // Los administradores tienen acceso a todos los usuarios
                                        if ($_SESSION["rol"] == 1) {
                                            echo "<label class='form-label' for='usuario'>Seleccionar usuario</label>";                           
                                            $resultado_seleccionar_usuarios = seleccionar_usuarios($conexion_PDO);
                                            if(!$resultado_seleccionar_usuarios["success"]){
                                                echo "<div class='alert alert-warning'>" . $resultado_seleccionar_usuarios["mensaje"] . "</div>"; 
                                            } else {  
                                                echo "<select class='form-select' name='usuario_id' id='usuario'>";
                                                echo "<option value='' selected disabled>Selecciona un usuario</option>";
                                                foreach($resultado_seleccionar_usuarios["datos"] as $usuario) {
                                                    echo "<option value='". $usuario->getId() . "'>" . $usuario->getUsername() . "</option>";                                             
                                                }
                                                echo "</select>";
                                            }
                                        } else if ($_SESSION["rol"] == 0) {
                                            // Seleccionar el id de usuario asociado a un username
                                            $username = $_SESSION["usuario"];
                                            // Crear nueva instancia de la clase Usuarios
                                            $usuario = new Usuarios($username);
                                            // Seleccionar el Id asociado a ese usuario
                                            $resultado_seleccionar_usuarios = seleccionar_id_username($conexion_PDO, $usuario);
                                            $id_usuario = $usuario->getId();
                                            echo "<input type='hidden' name='usuario_id' id='usuario' value='$id_usuario'>";
                                        }
                                        // Cerrar conexión PDO
                                        $conexion_PDO = null;
                                    }
                                ?>
                        </div>
                        <button type="submit" class="btn btn-success mb-3">Registrar</button>
                    </form>
                <section>
            </main>
        </div>
    </div>
    <!--footer-->
    <?php include 'footer.php'; ?>
</body>
</html>
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
                <section>
                    <form class="mb-5" action="nueva.php" method="post">
                        <div class="mb-3">
                            <label class="form-label" for="titulo">Titulo</label>
                            <!--Podría acotar el rango de números válidos para este campo-->
                            <input class="form-control" type="text" name="titulo" id="titulo" placeholder="Título de la tarea" required>
                        </div>
                        <div class="mb-3">
                            <!-- Podría poner un mínimo y un máximo de caracteres para este campo -->
                            <label class="form-label" for="descripcion">Descripcion</label>
                            <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="Descripción de la tarea" required>
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
                            <label class="form-label" for="usuario">Seleccionar usuario</label>                            
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
                                        $resultado_seleccionar_usuarios = seleccionar_usuarios($conexion_PDO);
                                        if(!$resultado_seleccionar_usuarios["success"]){
                                            echo "<div class='alert alert-warning'>" . $resultado_seleccionar_usuarios["mensaje"] . "</div>"; 
                                        } else {  
                                            echo "<select class='form-select' name='usuario' id='usuario'>";
                                            echo "<option value='' selected disabled>Selecciona un usuario</option>";
                                            foreach($resultado_seleccionar_usuarios["datos"] as $usuario) {
                                                echo "<option value='". $usuario['id'] . "'>" . $usuario['username'] . "</option>";                                             
                                            }
                                            echo "</select>";
                                        }
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
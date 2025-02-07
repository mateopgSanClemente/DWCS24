<?php include_once "head.php"; ?>
    <body>
        <!-- header -->
        <?php include_once "header.php"; ?>

        <div class='container-fluid d-flex flex-column'>
            <div class="row">
                <!-- menu -->
                <?php include_once "menu.php";?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Buscar Tareas</h2>
                    <section>
                        <form action="tareas.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <?php
                                    include_once "pdo.php";
                                    //Conexión PDO
                                    $resultado_conectar_PDO = conectar_PDO();
                                    if(!$resultado_conectar_PDO["success"]){
                                        echo "<div class='alert alert-danger'>" . $resultado_conectar_PDO["mensaje"] . "</div>";
                                    } else {
                                        //Seleccionar usuarios
                                        $conexion_PDO = $resultado_conectar_PDO["conexion"];
                                        $resultado_seleccionar = seleccionar_usuarios($conexion_PDO);

                                        //Comprobar que los datos se seleccionaron correctamente
                                        if(!$resultado_seleccionar["success"])
                                        {
                                            echo "<div class='alert alert-warning'>" . $resultado_seleccionar["datos"] . "</div>";
                                        }
                                        else
                                        {
                                            echo "<select class='form-select' name='username' id='username' required>";
                                            echo "<option value='' selected disabled>Selecciona un usuario</option>";
                                            foreach($resultado_seleccionar["datos"] as $usuario)
                                            {
                                                echo "<option value='" . $usuario['id'] . "'>" . $usuario["username"] . "</option>";
                                            }
                                            echo "</select>";
                                        }
                                        $conexion_PDO = null;
                                    }

                                ?>
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Seleccionar un estado</label>
                                <select name="estado" id="estado" class="form-select">
                                    <option value="" selected disabled>Selecciona un estado</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En proceso">En proceso</option>
                                    <option value="Completada">Completada</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success mb-3">Buscar</button>
                        </form>
                    </section>
                </main>
            </div>
        </div>
        <!-- footer -->
        <?php include_once "footer.php";?>
    </body>
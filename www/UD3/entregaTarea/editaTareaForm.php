<?php include_once("head.php"); ?>
<body>
    <!-- header -->
    <?php include_once("header.php"); ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- menu -->
            <?php include_once("menu.php"); ?>
            <main class="col-md-9 main-content">
                <h2 class="border-bottom pt-4 pb-2 mb-3">Modificar Tarea</h2>
                <?php
                    try
                    {
                        require_once("mysqli.php");
                        //Guardar el id de la tarea
                        $id_tarea = $_GET['id'];
    
                        //Crear conexión
                        $mysqli_con = conectar_mysqli();

                        //Seleccionar tarea a modificar
                        list($comprobacion, $resultado_consulta) = seleccionar_tarea_id($mysqli_con, $id_tarea);
                        if(!$comprobacion)
                        {
                            echo ("<div class='alert alert-warning' role='alert'>" . $resultado_consulta) . "</div>";
                        }
                        else
                        {
                            //Recuperados los datos los guardo en variables y los descodifico
                            
                            $tarea_titulo = htmlspecialchars_decode($resultado_consulta['titulo']);
                            $tarea_descripcion = htmlspecialchars_decode($resultado_consulta['descripcion']);
                            $tarea_estado = htmlspecialchars_decode($resultado_consulta['estado']);
                            $tarea_username_usuario = htmlspecialchars_decode($resultado_consulta['username']);
                            $tarea_id = htmlspecialchars_decode($resultado_consulta['id']);
                        }
                    }
                    catch (mysqli_sql_exception $e)
                    {
                        echo "<div class='alert alert-warning' role='alert'>" . "Error: " . $e->getMessage();
                    }
                    finally
                    {
                        cerrar_conexion($mysqli_con);
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
                                require_once("pdo.php");
                                list($PDO_con, $mensaje_estado_conexion) = conectar_PDO();
                                //Comprobar la conexión
                                if($PDO_con === false)
                                {
                                    echo "<div class='alert alert-warning'>" . $mensaje_estado_conexion . "</div>";
                                }
                                else
                                {   
                                    //Sería más eficiente usar una función que sólo selecciones el campo 'username' de la tabla usuarios.
                                    list($comprobacion, $resultado) = seleccionar_usuarios($PDO_con);
                                    if(!$comprobacion)
                                    {
                                        echo "<div class='alert alert-warning'>" . $resultado . "</div>"; 
                                    }
                                    else
                                    {   
                                        echo "<select class='form-select' name='usuario' id='usuario'>";
                                        foreach ($resultado as $usuario) {
                                            $selected = ($tarea_username_usuario === $usuario['username']) ? "selected='selected'" : "";
                                            //TODO: Conseguir que imprima selected en lugar de 'selected=""'.
                                            echo "<option value='" . $usuario['id'] . "' $selected>" . $usuario['username'] . "</option>";
                                        }                                        
                                        echo "</select>";
                                    }
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
    <?php include_once("footer.php"); ?>
</body>
 
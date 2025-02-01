<?php include_once("head.php"); ?>
<body>
    <!-- header -->
    <?php include_once("header.php"); ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- menu -->
            <?php include_once("menu.php"); ?>
            <main class="col-md-9 main-content">
                <h2 class="border-bottom pt-4 pb-2 mb-3">Lista de Tareas</h2>
                <?php
                if(empty($_POST))
                {
                    try
                    {
                        require_once("mysqli.php");

                        //Crear la conexion
                        $mysqli_conn = conectar_mysqli();

                        //Realizar consulta
                        /*
                        *   TODO: En tamaños pequeños de pantalla la tabla sobre sale por el eje horizontal,
                        *   habría que ajustar el tamaño de la fuente para que esto no pase.
                        */
                        list($comprobacion, $resultado) = seleccionar_tareas($mysqli_conn);

                        if(!$comprobacion)
                        {
                            echo "<div class='alert alert-warning'>" . $resultado . "</div>";
                        }
                        else
                        {
                            echo "<table class='table table-striped table-hover'>
                                <thead class='table-dark'>
                                    <tr>
                                        <th scope='col'>ID Tarea</th>
                                        <th scope='col'>Titulo</th>
                                        <th scope='col'>Descripción</th>
                                        <th scope='col'>Estado</th>
                                        <th scope='col'>Username</th>
                                        <th scope='col'>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>";
                            foreach($resultado as $tarea)
                            {
                                echo "<tr>";
                                foreach($tarea as $datos_tarea)
                                {
                                    echo "<td>" . $datos_tarea . "</td>";
                                }
                                echo "<td>";
                                echo "<a href='editaTareaForm.php?id=" . $tarea['id'] . "' class='btn btn-success btn-sm me-2'>Editar</a>";
                                echo "<a href='borraTarea.php?id=" . $tarea['id'] . "' class='btn btn-danger btn-sm me-2'>Eliminar</a>";
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        }
                    }
                    catch (Exception $e)
                    {
                        echo "<div class='alert alert-warning' role='alert'>" . "Error: " . $e->getMessage();
                    }
                    finally
                    {
                        cerrar_conexion($mysqli_conn);
                    }
                }
                else
                {
                    try
                    {
                        require_once("pdo.php");

                        //Crear la conexion
                        list($PDO_conn, $mensaje_estado_conexion) = conectar_PDO();
                        //TODO: comprobar conexión

                        //Variables de id de usuario y estado de la tarea
                        $id_usuario = $_POST["username"];
                        $comprobacion;
                        $resultado;
                        $estado_tarea;
                        list($comprobacion, $resultado) = seleccionar_tarea_username_estado($PDO_conn, $id_usuario);
                        if(isset($_POST["estado"]))
                        {
                            $estado_tarea = $_POST["estado"];
                            list($comprobacion, $resultado) = seleccionar_tarea_username_estado($PDO_conn, $id_usuario, $estado_tarea);
                        }

                        if(!$comprobacion)
                        {
                            echo "<div class='alert alert-warning'>" . $resultado . "</div>";
                        }
                        else
                        {
                            echo "<table class='table table-striped table-hover'>
                                <thead class='table-dark'>
                                    <tr>
                                        <th scope='col'>ID Tarea</th>
                                        <th scope='col'>Titulo</th>
                                        <th scope='col'>Descripción</th>
                                        <th scope='col'>Estado</th>
                                        <th scope='col'>Username</th>
                                        <th scope='col'>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>";
                            foreach($resultado as $tarea)
                            {
                                echo "<tr>";
                                foreach($tarea as $datos_tarea)
                                {
                                    echo "<td>" . $datos_tarea . "</td>";
                                }
                                echo "<td>";
                                echo "<a href='editaTareaForm.php?id=" . $tarea['id'] . "' class='btn btn-success btn-sm me-2'>Editar</a>";
                                echo "<a href='borraTarea.php?id=" . $tarea['id'] . "' class='btn btn-danger btn-sm me-2'>Eliminar</a>";
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        }
                    }
                    catch (Exception $e)
                    {
                        echo "<div class='alert alert-warning' role='alert'>" . "Error: " . $e->getMessage();
                    }
                }
                ?>
            </main>
        </div>
    </div>
    <!-- footer -->
    <?php include_once("footer.php"); ?>
</body>

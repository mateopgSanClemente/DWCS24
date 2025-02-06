<?php include_once "head.php"; ?>
<body>
    <!-- header -->
    <?php include_once "header.php"; ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- menu -->
            <?php include_once "menu.php"; ?>
            <main class="col-md-9 main-content">
                <h2 class="border-bottom pt-4 pb-2 mb-3">Lista de Tareas</h2>
                <?php
                /**
                 *  TODO:
                 *  - Adaptar el fichero a las nuevas fuciones de pdo y mysqli.
                 */
                if(empty($_POST)) {
                    require_once "mysqli.php";
                    // Crear la conexion
                    $resultado_conexion_mysqli = conectar_mysqli();
                    // Comprobar que la conexión fue exitosa
                    if (!$resultado_conexion_mysqli["success"]){
                        // Mostrar mensaje de error
                        echo "<div class='alert alert-danger' role='alert'>" . $resultado_conexion_mysqli["error"] . "</div>";
                    } else {
                        // Si la conexión fue exitosa, guardar la conexión en una variable por comodidad
                        $conexion_mysqli = $resultado_conexion_mysqli["conexion"];
                        // Seleccionar tarea
                        $resultado_seleccionar_tareas = seleccionar_tareas($conexion_mysqli);
                        //Comprobar que las tareas se seleccionaron correctamente
                        if (!$resultado_seleccionar_tareas["success"]){
                            // Mostrar mensaje de error
                            echo "<div class='alert alert-warning' role='alert'>" . $resultado_seleccionar_tareas["mensaje"] . "</div>";
                        } else {
                            // Recuperar tareas y mostrar en una tabla sus características y su usuario
                            $tareas = $resultado_seleccionar_tareas["datos"];
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
                                    foreach($tareas as $tarea)
                                    {
                                        echo "<tr>";
                                        foreach($tarea as $datos_tarea)
                                        {
                                            echo "<td>" . $datos_tarea . "</td>";
                                        }
                                        echo "<td>";
                                        echo "<a href='editaTareaForm.php?id=" . $tarea['id'] . "' class='btn btn-success btn-sm me-2'>Editar</a>";
                                        echo "<a href='borraTarea.php?id=" . $tarea['id'] . "' class='btn btn-danger btn-sm me-2'>Eliminar</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table>";
                        }
                        // Cerrar conexión
                        cerrar_conexion($conexion_mysqli);
                    }
                } else {
                    require_once "pdo.php";
                    // Crear la conexion
                    $resultado_conexion_PDO = conectar_PDO();
                    // Comprobar que la cnexión fue exitosa
                    if (!$resultado_conexion_PDO["success"]){
                        echo "<div class='alert alert-danger' role='alert'>" . $resultado_conexion_mysqli["mensaje"] . "</div>";
                    } else {
                        // Guardar la conexión en una variable
                        $conexion_PDO = $resultado_conexion_PDO["conexion"];
                        // ID usuario para mostrar tareas asociada.
                        $id_usuario = $_POST["username"];
                        // En caso de que se especifique el estado de la tarea
                        if(isset($_POST["estado"])) {
                            $estado_tarea = $_POST["estado"];
                            // Seleccionar las tareas por su id_usuario y estado
                            $resultado_seleccionar_tareas = tareas_usuario_estado($conexion_PDO, $id_usuario, $estado_tarea);
                            // Comprobar que se seleccionaron correctamente
                            if (!$resultado_seleccionar_tareas["success"]){
                                // Mostar mensaje
                                echo "<div class='alert alert-warning' role='alert'>" . $resultado_seleccionar_tareas["mensaje"] . "</div>";
                            } else {
                                // Crear una tabla dinámica
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
                                foreach($resultado_seleccionar_tareas["datos"] as $tarea)
                                {
                                    echo "<tr>";
                                    foreach($tarea as $datos_tarea)
                                    {
                                        echo "<td>" . $datos_tarea . "</td>";
                                    }
                                    echo "<td>";
                                    echo "<a href='editaTareaForm.php?id=" . $tarea['id'] . "' class='btn btn-success btn-sm me-2'>Editar</a>";
                                    echo "<a href='borraTarea.php?id=" . $tarea['id'] . "' class='btn btn-danger btn-sm me-2'>Eliminar</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody></table>";
                            }
                        }
                        // Cerrar conexión
                        $conexion_PDO = null;
                    }
                }
                ?>
            </main>
        </div>
    </div>
    <!-- footer -->
    <?php include_once("footer.php"); ?>
</body>

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
                <h2 class="border-bottom pt-4 pb-2 mb-3">Lista de Tareas</h2>
                <?php
                /**
                 *  TODO:
                 *  - Mostrar un página con la infomación detallada sobre la tarea cuando el fichero 
                 *  recive un id y este se corresponde con alguno de las tareas existentes
                 */
                // En caso de que el arrat $_GET no esté vacío
                if (!empty($_GET)){
                    // Convertir el tipo de dato en un entero
                    // Validar el contenido del array GET: El valor de la clave deber ser 'id' y el valor ser de tipo entero
                    if (isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] > 0){
                        // Convertir el valor del id a tipo entero
                        $id_tarea = intval($_GET["id"]);
                        require_once "pdo.php";
                        // Conexión PDO
                        $resultado_conexion_PDO = conectar_PDO();
                        // Comprobar conexión
                        if (!$resultado_conexion_PDO["success"]){
                            // Mostrar mensaje de error
                            echo "<div class='alert alert-danger' role='alert'>" . $resultado_conexion_PDO["mensaje"] . "</div>";
                        } else {
                            $conexion_PDO = $resultado_conexion_PDO["conexion"];
                            $resultado_tarea_id = seleccionar_tarea_id_PDO($conexion_PDO, $id_tarea);
                            // Comprobar que los id que se pasan a través de un método GET coinciden con el id de alguna tarea de la base de datos
                            if (!$resultado_tarea_id["success"]) {
                                echo "<div class='alert alert-warning' role='alert'>" . $resultado_tarea_id["mensaje"] . "</div>";
                            } else {
                                // Mostrar la información detallada de la tarea
                                // Recoger los datos de la tarea en variables
                                $tarea = $resultado_tarea_id["datos"];
                                $titulo_tarea = $tarea["titulo"];
                                $descripcion_tarea = $tarea["descripcion"];
                                $estado_tarea = $tarea["estado"];
                                $tarea_username = $tarea["username"];
                                echo "<div class='container mt-5'>
                                        <table class='table table-bordered'>
                                        <thead>
                                            <tr>
                                            <th colspan='2' class='text-center table-dark'>DETALLES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <th scope='row'>Título:</th>
                                            <td>" . $titulo_tarea . "</td>
                                            </tr>
                                            <tr>
                                            <th scope='row'>Descripción:</th>
                                            <td>" . $descripcion_tarea . "</td>
                                            </tr>
                                            <tr>
                                            <th scope='row'>Estado:</th>
                                            <td>" . $estado_tarea . "</td>
                                            </tr>
                                            <tr>
                                            <th scope='row'>Usuario:</th>
                                            <td>" . $tarea_username . "</td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class='container mt-4 mb-4'>
                                        <!-- Contenedor para Archivos adjuntos -->
                                        <div class='card'>
                                            <div class='card-header'>
                                            Archivos adjuntos
                                            </div>
                                            <div class='card-body'>
                                            <!-- Área para añadir archivo con borde punteado -->
                                            <div class='d-flex justify-content-center align-items-center' style='border: 2px dashed #ccc; height: 150px;'>
                                                <span class='h1 mb-0'>+</span>
                                            </div>
                                            <!-- Enlace o botón para añadir archivo adjunto -->
                                            <div class='text-center mt-3'>
                                                <a href='subidaFichForm.php' class='text-decoration-none'>Añadir archivo adjunto</a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>";
                            }
                        }
                    } else {
                        echo "<div class='alert alert-warning' role='alert'>El tipo de dato del formulario GET no es correcto</div>";
                    }
                } else if(empty($_POST) && empty($_GET)) {
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
                            /**
                             * TODO:
                             *  - Hacer todavía más dinámica la tabla utilizando un bucle para procesar los valores de las claves y crear los encabezados de cada columna
                             */
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
                                        echo "<a href='" . $_SERVER["PHP_SELF"] . "?id=" . $tarea['id'] . "' class='btn btn-primary btn-sm me-2'>Mostrar</a>";
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
                } else if (!empty($_POST)) {
                    require_once "pdo.php";
                    // Crear la conexion
                    $resultado_conexion_PDO = conectar_PDO();
                    // Comprobar que la cnexión fue exitosa
                    if (!$resultado_conexion_PDO["success"]){
                        echo "<div class='alert alert-danger' role='alert'>" . $resultado_conexion_PDO["mensaje"] . "</div>";
                    } else {
                        // Guardar la conexión en una variable
                        $conexion_PDO = $resultado_conexion_PDO["conexion"];
                        // ID usuario para mostrar tareas asociada.
                        $id_usuario = intval($_POST["username"]);
                        // En caso de que se especifique el estado de la tarea
                        
                            $estado_tarea = isset($_POST["estado"]) ? $_POST["estado"] : null;
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
                                foreach($resultado_seleccionar_tareas["datos"] as $tarea) {
                                    echo "<tr>";
                                    foreach($tarea as $datos_tarea)
                                    {
                                        echo "<td>" . $datos_tarea . "</td>";
                                    }
                                    echo "<td>";
                                    echo "<a href='" . $_SERVER["PHP_SELF"] . "?id=" . $tarea['id'] . "' class='btn btn-primary btn-sm me-2'>Mostrar</a>";
                                    echo "<a href='editaTareaForm.php?id=" . $tarea['id'] . "' class='btn btn-success btn-sm me-2'>Editar</a>";
                                    echo "<a href='borraTarea.php?id=" . $tarea['id'] . "' class='btn btn-danger btn-sm me-2'>Eliminar</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody></table>";
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

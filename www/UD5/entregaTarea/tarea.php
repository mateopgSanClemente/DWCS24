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
                <h2 class="border-bottom pt-4 pb-2 mb-3">Detalles tarea</h2>
                <?php
                // En caso de que el array $_GET no esté vacío
                // Mostrar mensaje informativo en caso de que el fichero se subiera correctamente o no
                if(isset($_SESSION["err_upload"])){
                    echo "<div class='alert alert-danger' role='alert'>{$_SESSION["err_upload"]}</div>";
                    unset($_SESSION["err_upload"]);
                } else if (isset($_SESSION["succ_upload"])){
                    echo "<div class='alert alert-success' role='alert'>{$_SESSION["succ_upload"]}</div>";
                    unset($_SESSION["succ_upload"]);
                }

                // Mostrar mensaje informativo para las descargas
                if(isset($_SESSION["err_descarga"])){
                    echo "<div class='alert alert-danger' role='alert'>{$_SESSION["err_descarga"]}</div>";
                    unset($_SESSION["err_descarga"]);
                }
                /**
                 *  TODO:
                 *  - Por algún motivo, no se muestra en la página el mensaje con el aviso
                 *  en caso de que el fichero se descargase sin problemas.
                 */
                //else if (isset($_SESSION["succ_descarga"])){
                  //  echo "<div class='alert alert-success' role='alert'>{$_SESSION["succ_descarga"]}</div>";
                  //  unset($_SESSION["succ_descarga"]);
                //}

                // Mostrar mensaje informativo para eliminación de ficheros
                if(isset($_SESSION["err_eliminar"])){
                    echo "<div class='alert alert-danger' role='alert'>{$_SESSION["err_eliminar"]}</div>";
                    unset($_SESSION["err_eliminar"]);
                } else if (isset($_SESSION["succ_eliminar"])){
                    echo "<div class='alert alert-success' role='alert'>{$_SESSION["succ_eliminar"]}</div>";
                    unset($_SESSION["succ_eliminar"]);
                }

                // Mostrar mensaje de error de conexión
                if(isset($_SESSION["errCon"])){
                    echo "<div class='alert alert-warning' role='alert'>{$_SESSION["errCon"]}</div>";
                    unset($_SESSION["errCon"]);
                }
                
                if (isset($_GET)){
                    // Convertir el tipo de dato en un entero
                    // Validar el contenido del array GET: El valor de la clave deber ser 'id' y el valor ser de tipo entero
                    if (!empty($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] > 0){
                        // Convertir el valor del id a tipo entero
                        $id_tarea = (int)($_GET["id"]);
                        require_once "pdo.php";

                        // Conexión PDO
                        $resultado_conexion_PDO = conectar_PDO();
                        // Comprobar conexión
                        if (!$resultado_conexion_PDO["success"]){
                            // Mostrar mensaje de error
                            echo "<div class='alert alert-danger' role='alert'>" . $resultado_conexion_PDO["mensaje"] . "</div>";
                        } else {
                            $conexion_PDO = $resultado_conexion_PDO["conexion"];
                            // Crear objeto de la clase Tareas
                            $tarea = new Tareas ($id_tarea);
                            $resultado_tarea_id = seleccionar_tarea_id_PDO($conexion_PDO, $tarea);
                            // Comprobar que los id que se pasan a través de un método GET coinciden con el id de alguna tarea de la base de datos
                            if (!$resultado_tarea_id["success"]) {
                                echo "<div class='alert alert-warning' role='alert'>" . $resultado_tarea_id["mensaje"] . "</div>";
                            } else {
                                // Recoger los datos de la tarea en variables
                                $tarea = $resultado_tarea_id["tarea"];
                                $titulo_tarea = $tarea->getTitulo();
                                $descripcion_tarea = $tarea->getDescripcion();
                                $estado_tarea = $tarea->getEstado();
                                $tarea_username = $tarea->getUsuario()->getUsername();
                                // Mostrar la información detallada de la tarea
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
                                    </div>";
                                // MOSTRAR FICHEROS
                                // Instanciar clase Ficheros
                                $fichero = new Ficheros (null, null, null, null, $tarea);

                                // Capturar excepciones DataBaseException
                                try{
                                    // Crear clase FicherosDBImp
                                    require_once "clases/ficherosDBImp.php";
                                    $ficherosDB = new FicherosDBImp($conexion_PDO);
                                    // Recoger información sobre los ficheros
                                    $resultado_seleccionar_archivos = $ficherosDB->listaFicheros($tarea->getId());
                                } catch (DataBaseException $e) {
                                    $_SESSION["errCon"] = $e;
                                    header("Location: tarea.php?id=" . $id_tarea);
                                }
                                echo "<div class='container mt-4 mb-4'>
                                <div class='card'>
                                    <div class='card-header'>
                                    Archivos adjuntos
                                    </div>
                                    <div class='card-body'>
                                    <div class='row d-flex align-items-stretc'>";
                                        
                                            foreach ($resultado_seleccionar_archivos as $fichero){
                                                echo "<div class='col-md-4 my-2'>
                                                <div class='card h-100'>
                                                    <div class='card-body'>
                                                        <h5 class='card-title'>" . $fichero->getNombre() . "</h5>
                                                        <p class='card-text'>" . $fichero->getDescripcion() . "</p>
                                                        <a href='descargaFichero.php?id=$id_tarea&id_fichero=" . $fichero->getId() . "' class='btn btn-success'>Descargar</a>
                                                        <a href='borraFichero.php?id=$id_tarea&id_fichero=" . $fichero->getId() . "' class='btn btn-danger' blank>Eliminar</a>
                                                    </div>        
                                                </div>
                                                </div>";
                                            }
    
                                        echo "<!-- Columna para añadir archivo -->
                                        <div class='col-md-4  my-2'>
                                        <div class='card h-100'>
                                        <div class='card-body d-flex flex-column justify-content-center align-items-center'>
                                            <i class='bi bi-plus-circle' style='font-size: 2rem;'></i>
                                            <a href='subidaFichForm.php?id=$id_tarea' class='text-decoration-none mt-2'>Añadir archivo adjunto</a>
                                        </div>
                                        </div>
                                        </div>";
                                    echo "</div>
                                    </div>
                                </div>
                                </div>";
                            }
                            $conexion_PDO = null;
                        }
                    } else {
                        echo "<div class='alert alert-warning' role='alert'>El tipo de dato del formulario GET no es correcto</div>";
                    }
                } else {
                    echo "<div class='alert alert-darnger' role='alert'>No se seleccionó ninguna tarea</div>";
                }
                ?>
            </main>
        </div>
    </div>
</body>
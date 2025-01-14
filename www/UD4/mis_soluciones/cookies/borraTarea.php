<?php include_once ("head.php");?>
    <body>
        <!-- header -->
        <?php include_once("header.php");?>
        <div class="container-fluid d-flex flex-column">
            <div class="row">
                <!-- menu -->
                <?php include_once("menu.php");?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado de la eliminación de la tarea</h2>
                    <?php
                        //Crear conexion
                        require_once("mysqli.php");
                        $mysqli_con = conectar_mysqli();
                        $id_tarea = $_GET['id'];
                        list($comprobacion, $resultado) = eliminar_tarea($mysqli_con, $id_tarea);

                        if(!$comprobacion)
                        {
                            echo ("<div class='alert alert-warning' role='alert'>" . $resultado . "</div>");
                        }
                        else
                        {
                            echo ("<div class='alert alert-success' role='alert'>" . $resultado . "</div>");
                        }
                    ?>
                </main>
            </div>
        </div>
        <?php include_once("footer.php");?>
    </body>
</html>
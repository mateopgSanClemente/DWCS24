<?php include_once ("head.php");?>
    <body>
        <!-- header -->
        <?php include_once("header.php");?>
        <div class="container-fluid d-flex flex-column">
            <div class="row">
                <!-- menu -->
                <?php include_once("menu.php");?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Estado de la eliminación de usuario</h2>
                    <?php
                        require_once("pdo.php");
                        //Crear y comprobar conecxion
                        require_once ("pdo.php");
                        
                        list($conexion, $mensaje_estado_conexion) = conectar_PDO();

                        if($conexion === false)
                        {
                            echo "<div class='alert alert-warning>" . $mensaje_estado_conexion . "</div>";
                        }
                        else
                        {
                            $id = $_GET['id'];
                            list($comprobacion, $resultado) = eliminar_usuario($conexion, $id);

                            if(!$comprobacion)
                            {
                                echo ("<div class='alert alert-warning' role='alert'>" . $resultado . "</div>");
                            }
                            else
                            {
                                echo ("<div class='alert alert-success' role='alert'>" . $resultado . "</div>");
                            }
                        }
                    ?>
                </main>
            </div>
        </div>
        <?php include_once("footer.php");?>
    </body>
</html>
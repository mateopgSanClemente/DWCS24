<?php include_once "head.php";?>
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
                        //Crear y comprobar conecxion
                        require_once "pdo.php";
                        // Crear conexión PDO
                        $resultado_conexion_PDO = conectar_PDO();
                        // Comprobar que la conexión fue exitosa
                        if(!$resultado_conexion_PDO["success"]){
                            echo "<div class='alert alert-danger' role='alert'>" . $resultado_conexion_PDO["error"] . "</div>";
                        } else {
                            // Guardar la conexión en una variable
                            $conexion_PDO = $resultado_conexion_PDO["conexion"];
                            $id_usuario = $_GET["id"];
                            // Eliminar usuario
                            $resultado_eliminar_usuario = eliminar_usuario($conexion_PDO, $id_usuario);
                            // Mostrar resultado
                            if (!$resultado_eliminar_usuario["success"]){
                                echo "<div class='alert alert-warning' role='alert'>" . $resultado_eliminar_usuario["mensaje"] . "</div>";
                            } else {
                                echo "<div class='alert alert-success' role='alert'>" . $resultado_eliminar_usuario["mensaje"] . "</div>";
                            }
                            // Cerrar conexión
                            $conexion_PDO = null;
                        }
                    ?>
                </main>
            </div>
        </div>
        <?php include_once "footer.php";?>
    </body>
</html>
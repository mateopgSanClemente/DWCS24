<!DOCTYPE html>
<html lang="es">
<!-- HEAD -->
<?php
    include_once "head.php"
?>
<body>
    <!-- HEADER -->
    <?php include_once "header.php"; ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- MENU -->
            <?php include_once "menu.php"; ?>
            <!-- MAIN --> 
            <main class="col-md-8 main-content">
                <div class="pt-4 pb-2 mb-3 border-bottom">
                    <h2>Lista de donantes</h2>
                </div>
                <?php
                    /**
                     * - Comprobar conexión
                     * - Obtener donantes
                     * - Colocar donantes en una tabla
                     * - Añadir los siguientes botones por donante:
                     *      - Registrar donación.
                     *      - Lista de donaciones del donantes.
                     *      - Eliminar donantes.
                     */
                    require_once "pdo.php";
                    // Crear conexion
                    $resultado_conexion = conexion_PDO("donacion");
                    if($resultado_conexion instanceof PDO){
                        // Realizar la consulta
                        list($comprobacion, $resultado) = seleccionar_info_donantes($resultado_conexion);
                        if($comprobacion){
                            echo "<table class='table table-striped table-hover'>
                                    <thead class='table-dark'>
                                        <tr>
                                            <th scope='col'>ID Usuario</th>
                                            <th scope='col'>Nombre</th>
                                            <th scope='col'>Apellidos</th>
                                            <th scope='col'>Edad</th>
                                            <th scope='col'>Grupo sanguineo</th>
                                            <th scope='col'>Código postal</th>
                                            <th scope='col'>Teléfono móvil</th>
                                            <th scope='col'>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            foreach($resultado as $usuario){
                                echo "<tr>";
                                foreach($usuario as $dato){
                                    echo "<td>$dato</td>";
                                }
                                echo "<td>
                                        <a href='registrar_historico.php?id_donante=" . $usuario['id'] . "' class='btn btn-success btn-sm me-2'>Registrar donación</a>
                                        <a class='btn btn-warning btn-sm me-2'>Listar donaciones</a>
                                        <a class='btn btn-danger btn-sm me-2'>Eliminar donante</a>
                                    </td>
                                    </tr>";
                            }
                            echo "</tbody></table>";
                        }else{
                            echo "<div class='alert alert-warning' role='alert'>Ocurrió un error a la hora de obtener los donantes: " . $resultado . "</div>";
                        }
                    }else{
                        echo "<div class='alert alert-warning' role='alert'>$resultado_conexion</div>";
                    }
                ?>
            </main>
        </div>
    </div>
    <!-- FOOTER -->
    <?php
    include_once "footer.php";
    ?>
</body>
</html>
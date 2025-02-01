<!DOCTYPE html>
<html lang="es">
    <?php
        include_once "head.php";
    ?>
<body>
    <?php
        include_once "header.php";
    ?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <?php
                include_once "menu.php";
            ?>
            <main class="col-md-8 main-content">
                <div class="pt-4 pb-2 mb-3 border-bottom">
                    <h2>Historico donante</h2>
                </div>
                <?php
                    require_once "pdo.php";
                    // Recoger id del donante
                    $id_donante = $_GET["id_donante"];
                    //Comprobar conexión
                    $resultado_conexion = conexion_PDO("donacion");
                    if ($resultado_conexion instanceof PDO){
                        // Realizar consulta
                        list($comprobacion_consulta, $resultado_consulta) = seleccionar_historico_donante($resultado_conexion, $id_donante);
                        
                        // Comprobar consulta
                        if($comprobacion_consulta){
                            // Mostrar los resultados en una tabla
                            echo "<table class='table table-striped table-hover'>
                                <thead class='table-dark'>
                                    <tr>
                                        <th scope='col'>Nombre</th>
                                        <th scope='col'>Apellidos</th>
                                        <th scope='col'>Edad</th>
                                        <th scope='col'>Grupo sanguineo</th>
                                        <th scope='col'>Fecha donación</th>
                                        <th scope='col'>Próxima donación</th>
                                    </tr>
                                </thead>
                                <tbody>";
                            foreach($resultado_consulta as $historico_donante){
                                echo "<tr>";
                                foreach($historico_donante as $datos_historico){
                                    echo "<td>" . $datos_historico . "</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        }else{
                            echo "<div class='alert alert-warning' role='alert'>$resultado_consulta</div>";                       
                        }
                        // Cerrar conexion PDO
                        $resultado_conexion = null;
                    }else{
                        echo "<div class='alert alert-warning' role='alert'>$resultado_conexion</div>";                       
                    }
                ?>
            </main>
        </div>
    </div>
    <?php
        include_once "footer.php";
    ?>
</body>
</html>
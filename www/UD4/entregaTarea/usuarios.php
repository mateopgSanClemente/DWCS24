<?php include_once("head.php"); ?>
<body>
    <!-- header -->
    <?php include_once("header.php");?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- menu -->
            <?php include_once("menu.php"); ?>
            <main class="col-md-9 main-content">
                <h2 class="border-bottom pt-4 pb-2 mb-3">Lista de Usuarios</h2>
                <?php
                    require_once("pdo.php");
                    list($conexion, $mensaje_estado_conexion) = conectar_PDO();

                    if($conexion === false)
                    {
                        echo "<div class='alert alert-warning>" . $mensaje_estado_conexion . "</div>";
                    }
                    else
                    {
                        //También podría utilizar la función list() para separar el resultado de seleccionar_usuarios() en variable.
                        list($comprobacion, $resultado) = seleccionar_usuarios($conexion);
                        if(!$comprobacion)
                        {
                            echo "<div class='alert alert-warning'>" . $resultado . "</div>";
                        }
                        else
                        {
                            echo "<table class='table table-striped table-hover'>
                                <thead class='table-dark'>
                                    <tr>
                                        <!-- Tener en cuenta el atributo scope para tecnologías de asistencia como lectores de pantalla -->
                                        <th scope='col'>ID Usuarios</th>
                                        <th scope='col'>Username</th>
                                        <th scope='col'>Nombre</th>
                                        <th scope='col'>Apellidos</th>
                                        <th scope='col'>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>";
                            
                            foreach($resultado as $usuario)
                            {
                                echo "<tr>";
                                foreach($usuario as $dato_usuario)
                                {
                                    echo "<td>" . $dato_usuario . "</td>";
                                }
                                echo "<td>";
                                echo "<a href='editaUsuarioForm.php?id=" . $usuario['id'] . "' class='btn btn-success btn-sm me-2'>Editar</a>";
                                echo "<a href='borraUsuario.php?id=" . $usuario['id'] . "' class='btn btn-danger btn-sm me-2'>Eliminar</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        }     
                    }
                ?>
            </main>
        </div>
    </div>
    <!-- footer -->
    <?php include_once ("footer.php");?>
</body>
</html>
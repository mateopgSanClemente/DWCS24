<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<?php
    include_once "head.php";
?>
<body class="d-flex flex-column min-vh-100">

<!-- Header -->
<?php
    include_once "header.php";
?>

<!-- Layout -->
<div class="container-fluid flex-grow-1">
    <div class="row">
        <!-- Sidebar -->
        <?php
            include_once "nav_menu.php";
        ?>
        <!-- Main Content -->
        <main class="col-md-9 main-content">

            <!-- Listar Usuarios -->
            <section id="listar" class="mb-4">
                <h2>Listar Usuarios</h2>
                <?php
                    // Mostrar éxito
                    if (isset($_SESSION["success"])){
                        echo "<div class='alert alert-success role='alert'>" . $_SESSION["success"] . "</div>";
                        unset($_SESSION["success"]);
                    } else if (isset($_SESSION["error"])){
                        echo "<div class='alert alert-warning role='alert'>" . $_SESSION["error"] . "</div>";
                        unset($_SESSION["error"]);
                    }
                ?>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Edad</th>
                            <th>Provincia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once("utils_bases_datos.php");

                            $conexion = conectar();

                            $resultados = listar_cliente($conexion);

                            if($resultados[0] === true)
                            {
                                $lista = $resultados[1];

                                foreach ($lista as $fila)
                                {
                                    echo "<tr>";
                                    foreach ($fila as $columna => $dato)
                                    {
                                        echo "<td>" . $dato . "</td>";
                                    }
                                    echo "<td>";
                                    echo "<a href='modificar.php?id=" . $fila['id'] . "&nombre=" . $fila['nombre'] . "&apellido=" . $fila['apellido'] . "' class='btn btn-primary btn-sm me-2'>Editar</a>";
                                    echo "<a href='eliminar.php?id=" . $fila['id'] . "' class='btn btn-danger btn-sm'>Eliminar</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }

                            }
                        ?>
                    </tbody>
                </table>
            </section>

        </main>
    </div>
</div>

<!-- Footer -->
<?php
    include_once "footer.php";
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

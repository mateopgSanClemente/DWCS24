<?php
    session_start();
    // Redirige al usuario al formulario de login en caso de que la sesión no exista.
    if (!isset($_SESSION["usuario"])){
        header("Location: login.php?error=sesion");
    }
    // Redirigir a index en caso de que la persona que pretende acceder lo haga sin sen administrador
    if ($_SESSION["rol"] !== 1) {
        header("Location: index.php");
        exit;
    }
?>
<?php include_once "head.php"; ?>
<body>
    <!-- header -->
    <?php include_once "header.php";?>
    <div class="container-fluid d-flex flex-column">
        <div class="row">
            <!-- menu -->
            <?php include_once "menu.php"; ?>
            <main class="col-md-9 main-content">
                <h2 class="border-bottom pt-4 pb-2 mb-3">Lista de Usuarios</h2>
                <?php
                    // Mesaje de error al eliminar usuario
                    if (isset($_SESSION["errorConPDO"])){
                        echo "<div class='alert alert-danger' role='alert'>" . $_SESSION["errorConPDO"] . "</div>";
                        unset($_SESSION["errorConPDO"]);
                    } else if (isset($_SESSION["errorDel"])){
                        echo "<div class='alert alert-warning' role='alert'>" . $_SESSION["errorDel"]. "</div>";
                        unset($_SESSION["errorDel"]);
                    } else if (isset($_SESSION["success"])) {
                        echo "<div class='alert alert-success' role='alert'>" . $_SESSION["success"] . "</div>";
                        unset($_SESSION["success"]);
                    }
                    require_once "pdo.php";
                    // Conexión PDO con la base de datos
                    $resultado_conexion_PDO = conectar_PDO();
                    // Variable que guarda la instancia PDO
                    $conexion_PDO = $resultado_conexion_PDO["conexion"];
                    if(!$resultado_conexion_PDO["success"]) {
                        echo "<div class='alert alert-danger'>" . $resultado_conexion_PDO["mensaje"] . "</div>";
                    } else {
                        // Seleccionar usuario
                        $resultado_seleccionar_usuarios = seleccionar_usuarios($conexion_PDO);
                        // Comprobar si el usuario se seleccionó correctamente
                        if (!$resultado_seleccionar_usuarios["success"]){
                            echo "<div class='alert alert-warning'>" . $resultado_seleccionar_usuarios["mensaje"] . "</div>";
                        } else {
                            // Si se seleccionó correctamenta, generar la tabla dinamicamente.
                            echo "<table class='table table-striped table-hover'>
                                <thead class='table-dark'>
                                    <tr>
                                        <!-- Tener en cuenta el atributo scope para tecnologías de asistencia como lectores de pantalla -->
                                        <th scope='col'>ID Usuarios</th>
                                        <th scope='col'>Username</th>
                                        <th scope='col'>Nombre</th>
                                        <th scope='col'>Apellidos</th>
                                        <th scope='col'>Rol</th>
                                        <th scope='col'>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>";       
                            foreach($resultado_seleccionar_usuarios["datos"] as $usuario) {
                                echo "<tr>";
                                    echo "<td>", $usuario->getId(), "</td>";
                                    echo "<td>", $usuario->getUsername(), "</td>";
                                    echo "<td>", $usuario->getNombre(), "</td>";
                                    echo "<td>", $usuario->getApellidos(), "</td>";
                                    // Para el rol, se mostrara 'usuario' en caso de que guarde el valor 0 y 'administrador en caso de que guarde 1.
                                    if ($usuario->getRol() === 0) {
                                        echo "<td>Usuario</td>";
                                    } else if ($usuario->getRol() === 1) {
                                        echo "<td>Administrador</td>";
                                    }
                                    echo "<td>";
                                    echo "<a href='editaUsuarioForm.php?id=" . $usuario->getId() . "' class='btn btn-success btn-sm me-2'>Editar</a>";
                                    echo "<a href='borraUsuario.php?id=" . $usuario->getId() . "' class='btn btn-danger btn-sm me-2'>Eliminar</a>";
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
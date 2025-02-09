<?php
    // Recuperar cookie
    $tema = $_COOKIE["tema"];
?>
<nav class="col-md-3 col-lg-3 d-md-block bg-<?php echo $tema; ?> sidebar p-3">
    <div class="container pt-3 pb-2 mb-3 border-bottom">
        <h2>Menú</h2>
    </div>
    <ul class="list-group">
        <li class="list-group-item"><a class="nav-link" href="index.php">Página principal</a></li>
        <li class="list-group-item"><a class="nav-link" href="nuevaForm.php">Nueva tarea</a></li>
        <li class="list-group-item"><a class="nav-link" href="tareas.php">Listar tareas</a></li>
        <?php
            if ($_SESSION["rol"] === 1){
                echo "<li class='list-group-item'><a class='nav-link' href='init.php'>Iniciar base de datos y tabla</a></li>";
                echo "<li class='list-group-item'><a class='nav-link' href='nuevoUsuarioForm.php'>Nuevo usuario</a></li>";
                echo "<li class='list-group-item'><a class='nav-link' href='usuarios.php'>Listar usuarios</a></li>";
                echo "<li class='list-group-item'><a class='nav-link' href='buscaTareas.php'>Buscar tareas de usuario</a></li>"; 
            }
        ?>
        <li class="list-group-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
    </ul>
    <form class="m-3 w-50" action="tema.php" method="post">
        <label for="tema" class="form-label">Tema</label>
        <select id="tema" name="tema" class="form-select mb-2" aria-label="Selector de tema">
            <option value="light" selected> Claro</option>
            <option value="dark">Oscuro</option>
            <option value="auto">Automático</option>
        </select>
        <button type="submit" class="btn btn-primary w-100">Aplicar</button>
    </form>
</nav>
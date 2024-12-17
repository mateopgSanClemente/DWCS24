<?php include_once("head.php"); ?>
    <body>
        <!-- header -->
        <?php include_once("header.php"); ?>

        <div class='container-fluid d-flex flex-column'>
            <div class="row">
                <!-- menu -->
                <?php include_once("menu.php");?>
                <main class="col-md-9 main-content">
                    <h2 class="pt-4 pb-2 mb-3 border-bottom">Buscar Tareas</h2>
                    <section>
                        <form action="tareas.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <?php
                                    include_once("pdo.php");
                                    //TODO: Generar de forma dinámica los usuarios que se pueden seleccionar a partir de los datos de la tabla.
                                ?>
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Seleccionar un estado</label>
                                <select name="estado" id="estado" class="form-select">
                                    <option value="" selected disabled>Selecciona un estado</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En proceso">En proceso</option>
                                    <option value="Completada">Completada</option>
                                </select>
                            </div>
                        </form>
                    </section>
                </main>
            </div>
        </div>
    </body>
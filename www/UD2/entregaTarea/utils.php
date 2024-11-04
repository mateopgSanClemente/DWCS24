<?php
    #Devolver listado de tareas (array). Estarán previamente almacenadas en una variable global de tipo array que contenga en cada posición a su vez un nuevo array para cada tarea (formato clave valor), que contenga: id, descripcion, estado (pendiente, en proceso o completada).

    $array_tareas = array (
        array (
            "id_tarea" => 1,
            "nombre_tarea" => "Limpiar",
            "estado_tarea" => "Pendiente"
        ),
        array (
            "id_tarea" => 2,
            "nombre_tarea" => "Cocinar",
            "estado_tarea" => "En proceso"
        ),
        array (
            "id_tarea" => 3,
            "nombre_tarea" => "Descansar",
            "estado_tarea" => "Completada"
        )
    );

    function devolver_tareas () {
        /*
        <div class="table">
    <table class="table table-striped table-hover">
        <thead class="thead">
            <tr>                            
                <th>Identificador</th>
                <th>Descriptción</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                ...
            </tr>
            */
        global $array_tareas;
        foreach ($array_tareas as $tarea) {
            echo "<tr>";
            foreach ($tarea as $valor) {
                echo "<td>$valor</td>";
            }   
            echo "</tr>";
        }
    };

    function filtrar_contenido ($campo) {
        $campo = trim ($campo);
        $campo = stripslashes ($campo);
        $campo = htmlspecialchars ($campo);
        return $campo;
    }
?>
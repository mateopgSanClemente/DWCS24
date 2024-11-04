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

    //Filtrar el contenido de un texto para que no contenga caracteres especiales, espacios en blanco duplicados, etc. Recibe la variable y la devuelve filtrada.
    function filtrar_contenido ($texto) {
        $texto = trim ($texto);
        $texto = stripslashes ($texto);
        $texto = htmlspecialchars ($texto);
        return $texto;
    }

    //Comprobar que un texto contiene información de texto válida, devolviendo true si se cumplen todos los requisitos o false si no es así. Deberá filtrar con la función anterior previamente antes de comprobar si es válido.
    function comprobar_campo ($campo) {
        $campo = filtrar_contenido ($campo);
        // Comprobar que el campo no está vacío
        if (empty($campo)) {
            return false;
        }
        
        // Longitud mínima y máxima, por probar
        /*
        if (strlen($campo) < 3 || strlen($campo) > 100) {
            return false;
        }
        */
        
        /* Comprobar si el campo solo contiene letras, números, y espacios
        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $campo)) {
            return false;
        }
        */
        
        // Si pasa todas las validaciones
        return true;
    }
?>
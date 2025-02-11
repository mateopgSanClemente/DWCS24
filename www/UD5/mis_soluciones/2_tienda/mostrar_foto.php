<?php
    require_once "utils_bases_datos.php";

    // Conexion mysqli
    $mysqli_con = conectar();

    list($exito, $resultado) = recuperar_foto($mysqli_con);

    cerrar_conexion($mysqli_con);

    header("Content-Type: image/jpg");
    echo $resultado;
?>
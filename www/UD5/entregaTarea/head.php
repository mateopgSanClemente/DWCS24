<!DOCTYPE html>
<?php
// Recuperar el valor de la cookie
if (isset($_COOKIE["tema"])){
    $tema = $_COOKIE["tema"];
}

?>
<html lang="es" data-bs-theme="<?php echo $tema??"light";?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD5. Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Conseguí ajustar el footer de esta forma pero me gustaría hacerlo mediante clases bootstrap -->
    <style>
        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px;
            border-top: 1px solid #ddd;
            bottom: 0;
            width: 100%;
        }
        
        body {
            height: 100%;
        }
        .main-content {
            padding-bottom: 100px;
        }
    </style>
</head>
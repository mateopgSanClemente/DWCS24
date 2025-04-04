<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lectura y escritura</title>
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
        <label for="comentario">Comentario</label>
        <textarea name="comentario" rows="10" cols="30"></textarea>
        <input type="submit" value="Comentar">
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $fichero = fopen("comentarios/comentarios.txt", "a+");
            fwrite($fichero, $_POST["comentario"] . "\n");
            fclose($fichero);
        }

        if(file_exists("comentarios/comentarios.txt")) {
            $fichero = fopen("comentarios/comentarios.txt", "r");
            while(!feof($fichero)){
                echo "<p>" . fgets($fichero) . "</p>";
            }
            fclose($fichero);
        }
    ?>
</body>
</html>
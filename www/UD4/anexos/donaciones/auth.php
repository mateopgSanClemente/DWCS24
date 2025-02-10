<?php
session_start();

require_once('database.php');



function comprobarUsuario($nombre, $pass, $conPDO)
{
    $consulta = "SELECT contrasena FROM administradores WHERE nombre_usuario=:nombre";
    $stmt = $conPDO->prepare($consulta);
    try
    {
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();

        //Si el usuario ya no existe, no valida
        if ($stmt->rowCount() != 1) return false;
        
        $fila=$stmt->fetch();
    
        $passBD=$fila['contrasena'];

        //Primero comprobamos que haya un usuario y después comprobamos la contraseña introducida
        if ($stmt->rowCount() == 1 && password_verify($pass, $passBD))
        {
            $usuario['nombre']=$nombre;
            return $usuario;
        }
        else
        {
            return null;
        }
    }
    catch (PDOException $ex)
    {
        return $ex->getMessage();
    }
    finally
    {
        $conPDO = null;
        if ($stmt != null) $stmt->closeCursor();
        $stmt = null;
    }

}

//Comprobar si se reciben los datos
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $usuario = $_POST["usuario"];
    $pass = $_POST["pass"];

    if (empty($usuario) || empty($pass))
    {
        header('Location: login.php?error=true&message=Los campos del formulario son obligatorios.');
    }

    $conPDO = conectaDonaciones();
    if (is_string($conPDO))
    {
        header('Location: login.php?error=true&message=' . $conPDO);
    }
    $user = comprobarUsuario($usuario, $pass, $conPDO);
    if(!$user)
    {
        header('Location: login.php?error=true');
    }
    elseif (is_string($user))
    {
        header('Location: login.php?error=true&message=' . $user);
    }
    else
    {
        $_SESSION['admin'] = $user;
        //Redirigimos a index.php
        header('Location: index.php');
    }
}
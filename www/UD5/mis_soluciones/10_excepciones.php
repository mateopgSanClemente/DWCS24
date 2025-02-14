<?php
/*
Defina una nueva clase de excepción llamada ExPropia que extienda de Excepcion. No debe hacer nada más.
Crea una clase llamada ExPropiaClass, con un método estático testNumber que recibe un número.
Si el número es cero lanza una excepción del tipo ExPropia. La excepción no está atrapada dentro de esta clase.
Lance el método testNumber con diferentes valores, capturando las posibles excepciones.
*/

class ExPropia extends Exception
{

}

class ExPropiaClass
{
    public static function testNumber(int $numero)
    {
        if($numero == 0)
        {
            throw new ExPropia("El valor del número no puede ser cero.");
        }
        return "El número es: $numero";
    }
}

try
{
    $numero1 = 1;
    $nuemro2 = 2;
    $nuemro0 = 0;

    ExPropiaClass::testNumber($numero1);
    ExPropiaClass::testNumber($nuemro2);
    ExPropiaClass::testNumber($nuemro0);
}
catch(ExPropia $e)
{
    echo "Excepcion capturada: ", $e->getMessage();
}
?>
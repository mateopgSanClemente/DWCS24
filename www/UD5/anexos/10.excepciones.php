<?php

class ExPropia extends Exception
{

}

class ExPropiaClass
{
    public static function testNumber($number)
    {
        if ($number == 0)
        {
            throw new ExPropia("El número no puede ser cero.");
        }
        return "El número es: $number";
    }
}

try
{
    echo ExPropiaClass::testNumber(5) . "<br>";
    echo ExPropiaClass::testNumber(0) . "<br>";
}
catch (ExPropia $e)
{
    echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
}
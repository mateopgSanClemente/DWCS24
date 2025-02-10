<?php

class Fecha
{
    private static string $calendario = "Calendario gregoriano";
    private static array $dias = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    private static array $meses =
    [
        "", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    public static function getCalendar(): string
    {
        return self::$calendario;
    }

    public static function getData(): string
    {
        $ano = date('Y');
        $mes = self::$meses[(int)date('m')];
        $dia = date('d');
        $diaSemana = self::$dias[date('w')];
        return "$diaSemana $dia de $mes del $ano";
    }

    public static function getHora(): string
    {
        return date('H:i:s');
    }

    public static function getDataHora(): string
    {
        return "Hoy es " . self::getData() . " y son las " . self::getHora();
    }
}

// Mostrar la salida
echo "Usamos el calendario: ", Fecha::getCalendar(), "<br>";
echo Fecha::getDataHora(), "<br>";

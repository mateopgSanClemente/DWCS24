<?php

class Data
{
    private static $calendario = "Calendario gregoriano";
    private static $nombreDias = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado"];
    private static $nombreMes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    public static function getData()
    {
        $ano = date('Y'); // Nos da el año actual 
        $mes = date('m'); // Representación numérica del mes, del 01 al 12.
        $dia = date('d'); // Representación numérica del día del mes, del 01 al 31.
        
        $diaSemanaNumero = date('w'); // Representación numérica del día de la semana, del 0 al 6, siendo 0 el domingo.
        
        // Eliminar el cero de $mes
        $mes = intval($mes);

        // Nombre del día de la semana
        $diaSemana = self::$nombreDias[$diaSemanaNumero];

        // Nombre del mes
        $mesNombre = self::$nombreMes[$mes];

        return "Hoy es $diaSemana $dia de $mesNombre del $ano";
    }

    public function getCalendar(): string {
        return self::$calendario;
    }

    // Devuelve la hora en el siguiente formato HH:MM:SS. (G, i, s)
    public function getHora(): string 
    {
        $hora = date('G');
        $minutos = date('i');
        $segundos = date('s');
        return " y son las $hora:$minutos:$segundos";
    }


    public function getDataHora(): string
    {
        return "Usamos el calendario: " . $this->getCalendar() . "<br>"
                . $this->getData() . " y son las " . $this->getHora();
    }
}

$fecha = new Data();
echo $fecha->getDataHora();

?>
<?php

class Alien
{
    private string $nombre;
    private static int $numberOfAliens = 0;

    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
        self::$numberOfAliens++;
    }

    public static function getNumberOfAliens(): int
    {
        return self::$numberOfAliens;
    }
}

// Crear objetos de la clase Alien
$alien1 = new Alien("Xenomorph");
$alien2 = new Alien("Predator");
$alien3 = new Alien("Martian");

// Mostrar el número de aliens creados
echo "Número de aliens creados: ", Alien::getNumberOfAliens(), "<br>";

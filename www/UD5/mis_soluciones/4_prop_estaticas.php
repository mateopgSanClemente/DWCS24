<?php

class Alien {
    private string $nombre;
    static private int $numberOfAliens = 0;

    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
        self::$numberOfAliens = self::$numberOfAliens+1;
    }

    static public function getNumberOfAliens(){
        return self::$numberOfAliens;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre){
        $this->nombre = $nombre;
    }

    public function __destruct()
    {
        self::$numberOfAliens = self::$numberOfAliens-1;
    }
}

$alien1 = new Alien("Pepino");
$alien2 = new Alien("Pedro");
$alien3 = new Alien("Ey");
echo Alien::getNumberOfAliens();
?>
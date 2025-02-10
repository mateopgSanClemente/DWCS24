<?php

class Participante {
    protected string $nombre;
    protected int $edad;

    public function __construct(string $nombre, int $edad)
    {
        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getEdad(): int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): void
    {
        $this->edad = $edad;
    }
}

class Jugador extends Participante
{
    private string $posicion;

    public function __construct(string $nombre, int $edad, string $posicion)
    {
        parent::__construct($nombre, $edad);
        $this->posicion = $posicion;
    }

    public function getPosicion(): string
    {
        return $this->posicion;
    }

    public function setPosicion(string $posicion): void
    {
        $this->posicion = $posicion;
    }
}

class Arbitro extends Participante {
    private int $anosArbitraje;

    public function __construct(string $nombre, int $edad, int $anosArbitraje)
    {
        parent::__construct($nombre, $edad);
        $this->anosArbitraje = $anosArbitraje;
    }

    public function getAnosArbitraje(): int
    {
        return $this->anosArbitraje;
    }

    public function setAnosArbitraje(int $anosArbitraje): void
    {
        $this->anosArbitraje = $anosArbitraje;
    }
}

// Crear objetos de las subclases
$jugador1 = new Jugador("Pedro", 25, "Delantero");
$jugador2 = new Jugador("Luis", 22, "Defensa");
$arbitro1 = new Arbitro("Carlos", 40, 10);
$arbitro2 = new Arbitro("Javier", 35, 8);

// Mostrar valores de los objetos
echo "Jugador 1: ", $jugador1->getNombre(), ", Edad: ", $jugador1->getEdad(), ", Posición: ", $jugador1->getPosicion(), "<br>";
echo "Jugador 2: ", $jugador2->getNombre(), ", Edad: ", $jugador2->getEdad(), ", Posición: ", $jugador2->getPosicion(), "<br>";
echo "Árbitro 1: ", $arbitro1->getNombre(), ", Edad: ", $arbitro1->getEdad(), ", Años arbitrando: ", $arbitro1->getAnosArbitraje(), "<br>";
echo "Árbitro 2: ", $arbitro2->getNombre(), ", Edad: ", $arbitro2->getEdad(), ", Años arbitrando: ", $arbitro2->getAnosArbitraje(), "<br>";

// Modificar valores
$jugador1->setNombre("Pablo");
$jugador1->setEdad(26);
$jugador1->setPosicion("Mediocampista");

$arbitro1->setNombre("Carlos Fernández");
$arbitro1->setEdad(41);
$arbitro1->setAnosArbitraje(11);

// Mostrar valores modificados
echo "Jugador 1 modificado: ", $jugador1->getNombre(), ", Edad: ", $jugador1->getEdad(), ", Posición: ", $jugador1->getPosicion(), "<br>";
echo "Árbitro 1 modificado: ", $arbitro1->getNombre(), ", Edad: ", $arbitro1->getEdad(), ", Años arbitrando: ", $arbitro1->getAnosArbitraje(), "<br>";

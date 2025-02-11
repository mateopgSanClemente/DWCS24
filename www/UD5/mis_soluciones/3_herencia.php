<?php 

class Participante {
    private string $nombre;
    private int $edad;

    public function __construct(string $nombre, int $edad) {
        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }
    public function setEdad(int $edad): void{
        $this->edad = $edad;
    }

    public function getNombre(): string{
        return $this->nombre;
    }
    public function getEdad(): int{
        return $this->edad;
    }
}

class Jugador extends Participante {
    private string $posicion;

    public function __construct(string $nombre, int $edad, string $posicion){
        parent::__construct($nombre, $edad);
        $this->posicion = $posicion;
    }

    public function setPosicion(string $posicion): void{
        $this->posicion = $posicion;
    }

    public function getPosicion(): string{
        return $this->posicion;
    }
}

class Arbitro extends Participante {
    private int $anhos;

    public function __construct(string $nombre, int $edad, int $anhos){
        parent::__construct($nombre, $edad);
        $this->anhos = $anhos;
    }

    public function setAnhos(int $anhos): void{
        $this->anhos = $anhos;
    }

    public function getAnhos(): int{
        return $this->anhos;
    }
}

$jugador1 = new Jugador("Mateo", 23, "Centro");
$jugador2 = new Jugador("Sara", 23, "Defensa");

$arbitro1 = new Arbitro("Daniel", 23, 5);
$arbitro2 = new Arbitro("Marina", 45, 23);

// Jugador 1
echo $jugador1->getNombre();
echo $jugador1->getEdad();
echo $jugador1->getPosicion();
$jugador1->setNombre("MateoMod");
$jugador1->setEdad(32);
$jugador1->setPosicion("Arriba");
echo $jugador1->getNombre();
echo $jugador1->getEdad();
echo $jugador1->getPosicion();

// Jugador 2
echo $jugador2->getNombre();
echo $jugador2->getEdad();
echo $jugador2->getPosicion();
$jugador2->setNombre("SaraMod");
$jugador2->setEdad(32);
$jugador2->setPosicion("Ataque");
echo $jugador2->getNombre();
echo $jugador2->getEdad();
echo $jugador2->getPosicion();

// Arbitro 1
echo $arbitro1->getNombre();
echo $arbitro1->getEdad();
echo $arbitro1->getAnhos();
$arbitro1->setNombre("DaniMod");
$arbitro1->setEdad(45);
$arbitro1->setAnhos(60);
echo $arbitro1->getNombre();
echo $arbitro1->getEdad();
echo $arbitro1->getAnhos();

// Arbitro 2
echo $arbitro2->getNombre();
echo $arbitro2->getEdad();
echo $arbitro2->getAnhos();
$arbitro2->setNombre("MarinaMod");
$arbitro2->setEdad(45);
$arbitro2->setAnhos(0);
echo $arbitro2->getNombre();
echo $arbitro2->getEdad();
echo $arbitro2->getAnhos();
?>

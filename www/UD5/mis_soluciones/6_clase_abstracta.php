<?php

abstract class Persona
{

    private int $id;
    protected string $nombre;
    protected string $apellidos;

    function __construct(int $id, string $nombre, string $apellidos)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
    }

    function getId(): int
    {
        return $this->id;
    }
    function getNombre(): string
    {
        return $this->nombre;
    }
    function getApellidos(): string
    {
        return $this->apellidos;
    }

    function setId(int $id): void
    {
        $this->id = $id;
    }
    function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    abstract function accion();
}

class Usuarios extends Persona 
{
    public function accion(): string
    {
        return "La persona $this->nombre $this->apellidos es usuario.";
    }
}

class Administradores extends Persona
{
    public function accion(): string
    {
        return "La persona $this->nombre $this->apellidos es administrador.";
    }
}

$usuario1 = new Usuarios(1, "Mateo", "Pastor");
$administrador = new Administradores(2, "Dani", "Lazare");

echo $usuario1->accion();
echo $administrador->accion();
?>
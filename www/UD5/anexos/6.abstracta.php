<?php

abstract class Persona
{
    private int $id;
    protected string $nombre;
    protected string $apellidos;

    public function __construct(int $id, string $nombre, string $apellidos)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
    }

    abstract public function accion(): string;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }
}

class Usuario extends Persona
{
    public function accion(): string
    {
        return "$this->nombre $this->apellidos está navegando en el sistema con rol <em>Usuario</em>.";
    }
}

class Administrador extends Persona
{
    public function accion(): string
    {
        return "$this->nombre $this->apellidos está gestionando el sistema con rol <em>Administrador</em>.";
    }
}

// Creación de objetos
$usuario = new Usuario(1, "Juan", "Pérez");
$admin = new Administrador(2, "Ana", "Gómez");

echo $usuario->accion(), "<br>";
echo $admin->accion(), "<br>";

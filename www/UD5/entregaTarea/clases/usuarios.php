<?php

class Usuarios {
    
    // Propiedades
    private ?int $id;
    private ?string $contrasena;
    private ?string $username;
    private ?string $nombre;
    private ?string $apellidos;
    private ?int $rol;

    // Contructor
    public function __construct(?string $username = null, ?string $nombre = null, ?string $apellidos = null, ?int $rol = null, ?string $contrasena = null, ?int $id = null)
    {
        $this->username = $username;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
        $this->id = $id;
    }

    // Getters
    public function getUsername(): string {
        return $this->username;
    }
    public function getNombre(): string {
        return $this->nombre;
    }
    public function getApellidos(): string {
        return $this->apellidos;
    }
    public function getContrasena(): string {
        return $this->contrasena;
    }
    public function getRol(): int {
        return $this->rol;
    }
    public function getId(): int {
        return $this->id;
    }

    // Setters
    public function setUsername(string $username): void {
        $this->username = $username;
    }
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }
    public function setApellidos(string $apellidos): void {
        $this->apellidos = $apellidos;
    }
    public function setContrasena(string $contrasena): void {
        $this->contrasena = $contrasena;
    }
    public function setRol(int $rol): void {
        $this->rol = $rol;
    }
    public function setId(?int $id): void {
        $this->id = $id;
    }
}   
?>
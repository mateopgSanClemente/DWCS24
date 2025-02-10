<?php

class Usuario
{
    private String $id;
    private String $nombre;
    private String $apellidos;
    private int $edad;
    private String $provincia;

    public function __construct(String $nombre = '', String $apellidos = '', int $edad = -1, String $provincia = '')
    {
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->edad = $edad;
        $this->provincia = $provincia;
    }

    public function getId(): String
    {
        return $this->id;
    }

    public function setId(String $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): String
    {
        return $this->nombre;
    }

    public function setNombre(String $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): String
    {
        return $this->apellidos;
    }

    public function setApellidos(String $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getEdad(): int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): void
    {
        $this->edad = $edad;
    }

    public function getProvincia(): String
    {
        return $this->provincia;
    }

    public function setProvincia(String $provincia): void
    {
        $this->provincia = $provincia;
    }

    public function __destruct()
    {
        // Destructor logic here if needed
    }

    public function validar(): array
    {
        $errores = [];

        if (empty($this->nombre) || !is_string($this->nombre) || strlen($this->nombre) < 3) {
            $errores['nombre'] = 'El nombre es obligatorio, debe ser una cadena de texto y tener al menos 3 caracteres.';
        }

        if (empty($this->apellidos) || !is_string($this->apellidos) || strlen($this->apellidos) < 3) {
            $errores['apellidos'] = 'Los apellidos son obligatorios, deben ser una cadena de texto y tener al menos 3 caracteres.';
        }

        if (empty($this->edad) || !filter_var($this->edad, FILTER_VALIDATE_INT) || $this->edad < 0) {
            $errores['edad'] = 'La edad es obligatoria y debe ser un número entero positivo.';
        }

        if (empty($this->provincia) || !is_string($this->provincia) || strlen($this->provincia) < 3) {
            $errores['provincia'] = 'La provincia es obligatoria, debe ser una cadena de texto y tener al menos 3 caracteres.';
        }

        return $errores;
    }
    
}
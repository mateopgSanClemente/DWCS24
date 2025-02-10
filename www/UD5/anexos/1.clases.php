<?php

class Contacto
{
    private string $nombre;
    private string $apellido;
    private string $telefono;

    public function __construct(string $nombre, string $apellido, string $telefono)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    public function muestraInformacion(): void
    {
        echo "Nombre: {$this->nombre}, Apellido: {$this->apellido}, Teléfono: {$this->telefono}<br>";
    }

    public function __destruct()
    {
        echo "Destruyendo el objeto de {$this->nombre} {$this->apellido}<br>";
    }
}

// Crear objetos de la clase Contacto
$contacto1 = new Contacto("Juan", "Pérez", "123456789");
$contacto2 = new Contacto("María", "Gómez", "987654321");
$contacto3 = new Contacto("Carlos", "López", "456123789");

// Mostrar valores con los getters
echo "Contacto 1: ", $contacto1->getNombre(), " ", $contacto1->getApellido(), ", Tel: ", $contacto1->getTelefono(), "<br>";
echo "Contacto 2: ", $contacto2->getNombre(), " ", $contacto2->getApellido(), ", Tel: ", $contacto2->getTelefono(), "<br>";
echo "Contacto 3: ", $contacto3->getNombre(), " ", $contacto3->getApellido(), ", Tel: ", $contacto3->getTelefono(), "<br>";

// Mostrar valores con muestraInformacion()
$contacto1->muestraInformacion();
$contacto2->muestraInformacion();
$contacto3->muestraInformacion();

// Destruir objetos explícitamente
unset($contacto1, $contacto2, $contacto3);

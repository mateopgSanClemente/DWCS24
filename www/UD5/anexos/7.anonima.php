<?php

// Creación de objetos con clase anónima
$triangulo1 = new class(10, 5)
{
    private float $base;
    private float $altura;

    public function __construct(float $base, float $altura)
    {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function area(): float
    {
        return ($this->base * $this->altura) / 2;
    }
};

$triangulo2 = new class(8, 4)
{
    private float $base;
    private float $altura;

    public function __construct(float $base, float $altura)
    {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function area(): float
    {
        return ($this->base * $this->altura) / 2;
    }
};

// Mostrar resultados
echo "Área del triángulo 1: " . $triangulo1->area() . "<br>";
echo "Área del triángulo 2: " . $triangulo2->area() . "<br>";

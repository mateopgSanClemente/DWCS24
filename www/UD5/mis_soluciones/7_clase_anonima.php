<?php

$triangulo1 = new class (10, 2.5) {
    private float $base;
    private float $altura;

    public function __construct(float $base, float $altura)
    {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function area(): float
    {
        return $this->base * $this->altura / 2;
    }
};

$triangulo2 = new class (30, 2.5) {
    private float $base;
    private float $altura;

    public function __construct(float $base, float $altura)
    {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function area(): float
    {
        return $this->base * $this->altura / 2;
    }
};

echo $triangulo1->area();
echo $triangulo2->area();
?>
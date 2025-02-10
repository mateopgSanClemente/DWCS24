<?php

trait CalculosCentroEstudiosTrait
{
    public function numeroDeAprobados($notas)
    {
        $aprobados = 0;
        foreach ($notas as $nota) {
            if ($nota >= 5)
            {
                $aprobados++;
            }
        }
        return $aprobados;
    }

    public function numeroDeSuspensos($notas)
    {
        return count($notas) - $this->numeroDeAprobados($notas);
    }

    public function notaMedia($notas)
    {
        return array_sum($notas) / count($notas);
    }
}

trait MostrarCalculosTrait
{
    public function saludo()
    {
        echo "Bienvenido al centro de cálculo<br>";
    }

    public function showCalculusStudyCenter($aprobados, $suspendidos, $notaMedia)
    {
        echo "Número de aprobados: $aprobados<br>";
        echo "Número de suspensos: $suspendidos<br>";
        echo "Nota media: $notaMedia<br>";
    }
}

class NotasTrait
{
    use CalculosCentroEstudiosTrait;
    use MostrarCalculosTrait;

    private $notas;

    public function __construct($notas)
    {
        $this->notas = $notas;
    }

    public function calcularYMostrar()
    {
        $aprobados = $this->numeroDeAprobados($this->notas);
        $suspendidos = $this->numeroDeSuspensos($this->notas);
        $notaMedia = $this->notaMedia($this->notas);

        $this->saludo();
        $this->showCalculusStudyCenter($aprobados, $suspendidos, $notaMedia);
    }
}


// Crear objeto de la clase NotasTrait
$notas_trait = new NotasTrait([7, 8, 5, 4, 6]);

// Calcular y mostrar resultados
$notas_trait->calcularYMostrar();
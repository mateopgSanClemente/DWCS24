<?php

interface CalculosCentroEstudios
{
    public function numeroDeAprobados();
    public function numeroDeSuspensos();
    public function notaMedia();
}

abstract class Notas
{
    protected $notas;

    public function __construct($notas)
    {
        $this->notas = $notas;
    }

    public function get_notas()
    {
        return $this->notas;
    }

    public function toString()
    {
        $listaDeNotas = "";
        foreach ($this->get_notas() as $nota)
        {
            $listaDeNotas .= "[$nota]";
        }
        return $listaDeNotas;
    }
}

class NotasDaw extends Notas implements CalculosCentroEstudios
{
    public function numeroDeAprobados()
    {
        $aprobados = 0;
        foreach ($this->notas as $nota)
        {
            if ($nota >= 5)
            {
                $aprobados++;
            }
        }
        return $aprobados;
    }

    public function numeroDeSuspensos()
    {
        $suspensos = 0;
        foreach ($this->notas as $nota)
        {
            if ($nota < 5)
            {
                $suspensos++;
            }
        }
        return $suspensos;
    }

    public function notaMedia() {
        $total = array_sum($this->notas);
        return round($total / count($this->notas), 3);
    }

}

// Test code
$notasArray = [4, 6, 8, 8, 3, 5, 7];
$notasDaw = new NotasDaw($notasArray);

echo "Notas: " . $notasDaw->toString() . "<br>";
echo "Número de aprobados: " . $notasDaw->numeroDeAprobados() . "<br>";
echo "Número de suspensos: " . $notasDaw->numeroDeSuspensos() . "<br>";
echo "Nota media: " . $notasDaw->notaMedia() . "<br>";
?>
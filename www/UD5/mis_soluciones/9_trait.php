<?php

// Cree un Trait llamado CalculosCentroEstudos con las mismas funciones que la interfaz del ejercicio anterior.
trait CalculosCentroEstudos
{
    public function numeroDeAprobados(array $notas): int
    {
        $numeroAprobados = 0;
        foreach($notas as $nota)
        {
            if($nota >= 5)
            {
                $numeroAprobados++;
            }
        }
        return $numeroAprobados;
    }

    public function numeroDeSuspensos(array $notas): int
    {
        $numeroDeSuspensos = 0;
        foreach($notas as $nota)
        {
            if($nota < 5)
            {
                $numeroDeSuspensos++;
            }
        }
        return $numeroDeSuspensos;
    }

    public function notaMedia(array $notas): float
    {
        $sumatorioNotas = 0;

        // Verificar que el array no este vacío para evitar la división por cero;
        if (empty($notas))
        {
            return 0.0;
        }
        // Numero de total de notas
        $numeroDeNotas = count($notas);
        // Sumatorio de notas
        $sumatorioNotas = array_sum($notas);
        // Calculamos la media
        $media = $sumatorioNotas/$numeroDeNotas;
        // Retornar con el valor redondeado a dos decimales
        return round($media, 2);
    }
}

// Cree otro Trait denominado MostrarCalculos con dos funciones:

// la función de saludo que muestra “Bienvenido al centro de cálculo”
// la función showCalculusStudyCenter, que recibe el número de aprobados, suspensos y la calificación promedio y los muestra en la pantalla dándoles formato.

trait MostrarCalculos
{
    public function saludo(): void
    {
        echo "Bienvenido el centro de cálculo.";
    }

    public function showCalculusStudyCenter(int $numeroAprobados, int $numeroDeSuspensos, float $notaMedia): void
    {
        echo "<br>Número de aprobados: $numeroAprobados
              <br>Número de suspensos: $numeroDeSuspensos
              <br>Nota media: $notaMedia";
    }
}

class NotasTrait
{
    use CalculosCentroEstudos;
    use MostrarCalculos;

    private $notas;

    public function __construct(array $notas)
    {
        $this->notas = $notas;        
    }

    public function getNotas()
    {
        return $this->notas;
    }

    public function setNotas(array $notas){
        $this->notas = $notas;
    }

    public function mostrarCalculos(){
        // Numero de aprobados
        $numeroAprobados = $this->numeroDeAprobados($this->notas);
        // Numero de suspensos
        $numeroSuspensos = $this->numeroDeSuspensos($this->notas);
        // Nota media
        $notaMedia = $this->notaMedia($this->notas);
        // Mostrar saludo
        $this->saludo();
        // Mostar datos
        $this->showCalculusStudyCenter($numeroAprobados, $numeroSuspensos, $notaMedia);
    }
}

$prueba = new NotasTrait([2, 4, 5, 7, 7, 7, 9]);
$prueba->mostrarCalculos();


?>

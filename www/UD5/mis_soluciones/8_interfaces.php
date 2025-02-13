<?php
    interface CalculosCentroEstudio {
        public function numeroDeAprobados(): int;
        public function numeroDeSuspensos(): int;
        public function notaMedia(): float;
    }

    abstract class Notas {

        private array $notas;

        public abstract function toString(): string;

        public function __construct(array $notas)
        {
            $this->notas = $notas;
        }

        public function getNotas(): array
        {
            return $this->notas;
        }

        public function setNotas(array $notas): void
        {
            $this->notas = $notas;
        }
    }

    class NotasDaw extends Notas implements CalculosCentroEstudio
    {
        public function toString(): string
        {
            return implode(", ", $this->getNotas());
        }

        public function numeroDeAprobados(): int
        {
            $numeroAprobados = 0;
            foreach($this->getNotas() as $nota)
            {
                if($nota >= 5)
                {
                    $numeroAprobados++;
                }
            }
            return $numeroAprobados;
        }

        public function numeroDeSuspensos(): int
        {
            $numeroDeSuspensos = 0;
            foreach($this->getNotas() as $nota)
            {
                if($nota < 5)
                {
                    $numeroDeSuspensos++;
                }
            }
            return $numeroDeSuspensos;
        }

        public function notaMedia(): float
        {
            $sumatorioNotas = 0;

            // Verificar que el array no este vacío para evitar la división por cero;
            if (empty($this->getNotas()))
            {
                return 0.0;
            }
            // Numero de total de notas
            $numeroDeNotas = count($this->getNotas());
            // Sumatorio de notas
            $sumatorioNotas = array_sum($this->getNotas());
            // Calculamos la media
            $media = $sumatorioNotas/$numeroDeNotas;
            // Retornar con el valor redondeado a dos decimales
            return round($media, 2);
        }
    }

    $notas = [4, 4, 4, 8, 8, 8, 9];
    $notas_curso = new NotasDaw($notas);

    echo $notas_curso->numeroDeAprobados();
    echo "<br>";
    echo $notas_curso->numeroDeSuspensos();
    echo "<br>";
    echo $notas_curso->notaMedia();
    echo "<br>";
    echo $notas_curso->toString();
?>
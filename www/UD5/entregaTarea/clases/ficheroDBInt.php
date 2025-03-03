<?php
    interface FicherosDBInt {
        public function listaFicheros($id_tarea): array;
        public function buscaFichero($id): Ficheros;
        public function borraFichero($id): bool;
        public function nuevoFichero(Ficheros $fichero): bool;
    }
?>
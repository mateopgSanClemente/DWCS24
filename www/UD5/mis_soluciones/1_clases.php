<?php
/**
 * 
 *   Cree una clase Contacto, con las siguientes propiedades privadas: nombre, apellido y número de teléfono.
 *   Definir un constructor con 3 argumentos, que asigne los valores a las propiedades.
 *   Genera los getters y los setters para todas las propiedades y el método muestraInformacion() que imprima los valores de las propiedades.
 *   Crea 3 objetos de la clase Contacto, asigne valores a todas sus propiedades y muéstrelos a continuación sus valores, 
 *   primero con los métodos get() y luego con el método muestraInformacion().
 *   Agregue un método __destruct(), que muestra en pantalla el objeto que se está destruyendo.
 */

    class Contacto {
        private string $nombre;
        private string $apellido;
        private string $numero_telefono;

        public function __construct(string $nombre, string $apellido, int $numero_telefono){
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->numero_telefono = $numero_telefono;
        }

        public function getNombre() : string {
            return $this->nombre;
        }
        public function getApellido() : string {
            return $this->apellido;
        }
        public function getNumeroTelefono() : int {
            return $this->numero_telefono;
        }

        public function setNombre($nombre) : void {
            $this->nombre = $nombre;
        }
        public function setApellidos($apellido) : void {
            $this->apellido = $apellido;
        }
        public function setNumeroTelefono($numero_telefono) : void {
            $this->numero_telefono = $numero_telefono;
        }

        public function muestraInformacion(){
            echo "Nombre: " . $this->nombre . "<br>";
            echo "Apellidos: " . $this->apellido . "<br>";
            echo "Numero de telefono: " . $this->numero_telefono . "<br>";
        }

        function __destruct()
        {
            echo "Destruyendo el objeto:<br>";
            self::muestraInformacion();
        }
    }

    $contacto1 = new Contacto("Mateo", "Pastor", 1);
    $contacto2 = new Contacto("Daniel", "Lazare", 2);
    $contacto3 = new Contacto("Sara", "Mon", 3);

    echo $contacto1->getNombre();
    echo $contacto1->getApellido();
    echo $contacto1->getNumeroTelefono();

    echo $contacto2->getNombre();
    echo $contacto2->getApellido();
    echo $contacto2->getNumeroTelefono();

    echo $contacto3->getNombre();
    echo $contacto3->getApellido();
    echo $contacto3->getNumeroTelefono();

    $contacto1->muestraInformacion();
    $contacto2->muestraInformacion();
    $contacto3->muestraInformacion();

    unset($contacto1, $contacto2, $contacto3);
?>
<?php
    class Usuario {
        private int $id;
        private string $nombre;
        private string $apellido;
        private int $edad;
        private string $provincia;

        public function __construct(string $nombre, string $apellido, int $edad, string $provincia){
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->edad = $edad;
            $this->provincia = $provincia;
        }

        public function getId(): int {
            return $this->id;
        }
        public function getNombre(): string {
            return $this->nombre;
        }
        public function getApellido(): string {
            return $this->apellido;
        }
        public function getEdad(): int {
            return $this->edad;
        }
        public function getProvincia(): string {
            return $this->provincia;
        }

        public function setId(int $id):void {
            $this->id = $id;
        }
        public function setNombre(string $nombre):void {
            $this->nombre = $nombre;
        }
        public function setApellido(string $apellido): void {
            $this->apellido = $apellido;
        }
        public function setEdad(int $edad): void {
            $this->edad = $edad;
        }
        public function setProvincia(string $provincia): void {
            $this->provincia = $provincia;
        }

        // Si no voy sobrescribir el método __destruct mejor no tocarlo
        /*
        public function __destruct(){
            
        }
        */

        /**
         * Validar las propiedades
         *  - Nombre:
         *      - Obligatorio.
         *      - String
         *      - Más de 3 letras
         *      - Menos de 50 letras.
         *  - Apellidos:
         *      - Obligatorio.
         *      - String.
         *      - Más de 3 letras.
         *      - Menos de 100 letras.
         */
        public function validar():array {
            $errores = [];

            // Nombre
            if (empty($this->nombre)){
                $errores["nombre"] = "El campo nombre es obligatorio.";
            } else if (!is_string($this->nombre)){
                $errores["nombre"] = "El campo nombre debe ser una cadena de texto.";
            } else if (strlen($this->nombre) < 3 || strlen($this->nombre) > 50){
                $errores["nombre"] = (strlen($this->nombre) < 3)
                    ? "El campo nombre debe tener más de 3 caractes."
                    : "El campo nombre debe tener menos de 50 caractes.";
            }
            // Apellidos
            if (empty($this->apellido)){
                $errores["apellido"] = "El campo apellido es obligatorio.";
            } else if (!is_string($this->apellido)){
                $errores["apellido"] = "El campo apellido debe ser una cadena de texto.";
            } else if (strlen($this->apellido) < 3 || strlen($this->apellido) > 100){
                $errores["apellido"] = (strlen($this->apellido) < 3)
                    ? "El campo apellido debe tener más de 3 caractes."
                    : "El campo apellido debe tener menos de 100 caractes.";
            }

            // Edad
            if (empty($this->edad)){
                $errores["edad"] = "El campo edad es obligatorio.";
            } else if (filter_var($this->edad, FILTER_VALIDATE_INT) || $this->edad < 18){
                $errores["edad"] = "El campo edad debe ser un número entero mayor o igual a 18.";
            }

            // Provincia
            $provincias_validas = ["pontevedra", "a corunha", "lugo", "ourense"];
            if (empty($this->provincia)){
                $errores["provincia"] = "El campo provincia es obligatorio.";
            } else if (!is_string($this->provincia)){
                $errores["provincia"] = "El campo provincia debe ser una cadea de texto.";
            } else if (!in_array(strtolower($this->provincia), $provincias_validas)){  
                $errores["provincia"] = "El campo provincia solo puede contener los valores 'pontevedra', 'a corunha', 'lugo' y 'ourense'.";
            }

            // Retornar resultado de la validación
            if (!empty($errores)){
                return ["success" => true, "resultado" => $errores];
            }

            return ["success" => false, "resultado" => "No se encontrarón errores en los campos"];
        }
    }
?>
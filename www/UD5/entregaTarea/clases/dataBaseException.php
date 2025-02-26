<?php
    /**
     * @link https://www.php.net/manual/en/language.exceptions.extending.php Extender excepciones.
     */
    class DataBaseException extends Exception{
        // Mysqli o PDO
        private string $method;
        // Consulta sql
        private string $sql;

        public function __construct(string $method, string $sql, string $message)
        {
            $this->method = $method;
            $this->sql = $sql;
            $message = "El método '$method' no pudo ejecutar la siguiente consulta SQL: $sql. Error en la conexión PDO: $message";
            parent::__construct($message);
        }

        // Getters
        public function getMethod():string {
            return $this->method;
        }
        public function getSql():string {
            return $this->sql;
        }

        // Setters
        public function setMethod(string $method): void {
            $this->method = $method;
        }
        public function setSql(string $sql): void {
            $this->sql = $sql;
        }
    } 
?>
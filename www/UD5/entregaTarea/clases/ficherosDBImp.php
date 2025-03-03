<?php
    require_once "ficheroDBInt.php";    
    class FicherosDBImp implements FicherosDBInt {
        // Atributos
        private PDO $conexion_PDO;

        public function __construct(PDO $conexion_PDO)
        {
            $this->conexion_PDO = $conexion_PDO;
        }


        // Métodos
        public function listaFicheros($id_tarea): array {
            try {
                $sql = "SELECT * FROM ficheros WHERE id_tarea = :id_tarea";
                $stmt = $this->conexion_PDO->prepare($sql);
                $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                // Convertir los resultados en objetos Ficheros
                return array_map(function ($fichero) {
                    return new Ficheros(
                        $fichero["id"],
                        htmlspecialchars_decode($fichero["nombre"]),
                        htmlspecialchars_decode($fichero["file"]),
                        htmlspecialchars_decode($fichero["descripcion"]),
                        new Tareas($fichero["id_tarea"])
                    );
                }, $resultado);
    
            } catch (PDOException $e) {
                throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
            }
        }

        public function buscaFichero($id): Ficheros{
             try {
                $sql = ("SELECT `file` FROM ficheros WHERE id = :id");
                $stmt = $this->conexion_PDO->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetch();
                // Decodificar los datos
                $resultado = array_map(function($fichero){
                    return new Ficheros (
                        null,
                        null,
                        htmlspecialchars_decode($fichero)
                    );
                }, $resultado);
                return $resultado[0];
             } catch (PDOException $e) {
                throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
             }
        }

        public function borraFichero($id): bool {
            try {
                $sql = ("DELETE FROM ficheros WHERE id = :id");
                $stmt = $this->conexion_PDO->prepare($sql);
                $stmt->bindParam(":id", $id);
                return $stmt->execute();
            } catch (PDOException $e) {
                throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
            }
        }

        public function nuevoFichero(Ficheros $fichero): bool {
            try {
                if ($fichero->getDescripcion() !== null) {
                    $sql = "INSERT INTO ficheros (nombre, `file`, descripcion, id_tarea) 
                            VALUES (:nombre, :file, :descripcion, :id_tarea)";
                } else {
                    $sql = "INSERT INTO ficheros (nombre, `file`, id_tarea) 
                            VALUES (:nombre, :file, :id_tarea)";
                }
                $stmt = $this->conexion_PDO->prepare($sql);
                $stmt->bindParam(":nombre", $fichero->getNombre(), PDO::PARAM_STR);
                $stmt->bindParam(":file", $fichero->getFile(), PDO::PARAM_STR);
                $stmt->bindParam(":id_tarea", $fichero->getTareas()->getId(), PDO::PARAM_STR);
                if ($fichero->getDescripcion() !== null) {
                    $stmt->bindParam(":descripcion", $fichero->getDescripcion(), PDO::PARAM_STR);
                }
                return $stmt->execute();
            } catch (PDOException $e) {
                throw new DataBaseException(__FUNCTION__, $sql, $e->getMessage());
            }
        }
    }
?>
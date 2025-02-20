<?php
/**Debe incluir las propiedades correspondientes ya existentes: id, nombre, file, descripcion y tarea.
 * Serán privados y tendrán sus getters y setters.
 * Implementa el constructor correspondiente.
 * Tendrá también dos constantes extra estáticas: FORMATOS, MAX_SIZE. Serán públicos.
 * Implementa un método estático que valide los campos y devuelva un array con errores. El array será asociativo, siendo la clave el nombre del atributo.
 * Actualiza los métodos de modelo (insert, delete, select) que afecten a los ficheros para que reciban y devuelvan objetos de esta clase.
 * Actualiza las vistas para que utilicen la nueva clase y nuevas funcionalidades de la misma.
 * 
 *  PREGUNTAS:
 *  - Es necesario el uso de la palabra clave 'static' en las constantes? Cuál es la diferencia entre usarla y no?
*/

class Ficheros {
    // Propiedades
    private ?int $id;
    private ?string $nombre;
    private ?string $file;
    private ?string $descripcion;
    private ?Tareas $tarea;

    // Constantes
    public const FORMATOS = ["image/jpeg", "image/png", "application/pdf"];
    // Tamaño máximo: 20MB
    public const MAX_SIZE = 20000000;

    // Constructor
    function __construct(?int $id = null, ?string $nombre = null, ?string $file = null, ?string $descripcion = null, ?Tareas $tarea = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->file = $file;
        $this->descripcion = $descripcion;
        $this->tarea = $tarea;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setNombre (string $nombre): void {
        $this->nombre = $nombre;
    }
    public function setFile (string $file): void {
        $this->file = $file;
    }
    public function setDescripcion (string $descripcion): void {
        $this->descripcion = $descripcion;
    }
    public function setTareas (Tareas $tarea): void {
        $this->tarea = $tarea;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getNombre(): ?string {
        return $this->nombre;
    }
    public function getFile(): ?string {
        return $this->file;
    }
    public function getDescripcion(): ?string {
        return $this->descripcion;
    }
    public function getTareas(): ?Tareas {
        return $this->tarea;
    }

    /**
     * Método para realizar la validación de campos del formulario para introducir un nuevo fichero:
     */
    static public function validarCampos (?string $nombre, ?string $descripcion, ?array $fichero): array|bool {
        $errores = [
            "nombre" => [],
            "file" => [],
            "descripcion" => []
        ];

        // Validar nombre
        if(empty($nombre)){
            $errores ["nombre"][] = "El campo 'nombre' es obligatorio."; 
        } else if (is_string($nombre) && strlen($nombre) < 3) {
            $errores ["nombre"][] = "El campo 'nombre' debe contener al menos 3 caracteres.";
        } else if (is_string($nombre) && strlen($nombre) > 100) {
            $errores ["nombre"][] = "El campo 'nombre' no debe contener mas de 100 caracteres.";
        }

        // Validar descripcion
        if (!empty($descripcion) && strlen($descripcion) < 3){
            $errores ["descripcion"][] = "El campo 'descripción' no debe contener menos de 3 caracteres.";
        } else if (strlen($descripcion) > 250) {
            $errores ["descripcion"][] = "El campo 'descripción' no debe contener más de 250 caracteres.";
        }

        // Validar fichero

        // 1. Comprobar que el fichero se subió correctamente
        if (empty($fichero["name"])){
            $errores["file"][] = "El campo fichero es obligatorio";
        } else {

            // 2. Recuperar la extensión del fichero para comprobar que está dentro de las permitidas (constante FORMATOS)
            /**
             *  Uso de FileInfo para obtener el tipo MIME del fichero.
             */
            // 2.1. Obtener la ruta del archivo subido temporalmente
            $archivo_tmp = $fichero["tmp_name"];
            // 2.2. Crear un recurso FileInfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // 2.3. Obtener el tipo MIME del fichero.
            $tipo_mime_fichero = finfo_file($finfo, $archivo_tmp);
            // 2.4. Cerrar el recurso FileInfo
            finfo_close($finfo);
            $type_file = strtolower(pathinfo(basename($fichero["name"]), PATHINFO_EXTENSION));
            if (!in_array($tipo_mime_fichero, self::FORMATOS)){
                $errores["file"][] = "Sólo se admiten extensiones 'jpg', 'png', 'pdf'.";
            }

            // 3. Recuperar el tamaño y comprobar que no supera los 20MB.
            if ($fichero["size"] > self::MAX_SIZE){
                $errores["file"][] = "El fichero no puede superar los 20MB.";
            }
        }

        $errores = array_filter($errores);
        if (!empty($errores)){
            return $errores;
        }
        return true;
    }
}
?>
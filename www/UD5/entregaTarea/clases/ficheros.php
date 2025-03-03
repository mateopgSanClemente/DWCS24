<?php

/**
 * Clase Ficheros
 *
 * Representa un fichero asociado a una tarea, incluyendo propiedades
 * como id, nombre, ruta del archivo, descripción y la tarea relacionada.
 * Proporciona métodos para establecer y obtener estos valores, así como
 * una función estática para validar los campos de un formulario de subida de ficheros.
 */
class Ficheros {

    /**
     * Identificador único del fichero.
     *
     * @var int|null
     */
    private ?int $id;

    /**
     * Nombre del fichero.
     *
     * @var string|null
     */
    private ?string $nombre;


    /**
     * Ruta o nombre del archivo en el sistema.
     *
     * @var string|null
     */
    private ?string $file;

    /**
     * Descripción del fichero.
     *
     * @var string|null
     */
    private ?string $descripcion;

    /**
     * Tarea asociada al fichero.
     *
     * @var Tareas|null
     */
    private ?Tareas $tarea;

    /**
     * Tipos MIME permitidos para los ficheros.
     *
     * @var array
     */
    public const FORMATOS = ["image/jpeg", "image/png", "application/pdf"];

    /**
     * Tamaño máximo permitido para los ficheros (20MB).
     *
     * @var int
     */
    public const MAX_SIZE = 20000000;

    /**
     * Constructor de la clase Ficheros.
     *
     * @param int|null    $id          Identificador único del fichero.
     * @param string|null $nombre      Nombre del fichero.
     * @param string|null $file        Ruta o nombre del archivo en el sistema.
     * @param string|null $descripcion Descripción del fichero.
     * @param Tareas|null $tarea       Tarea asociada al fichero.
     */
    function __construct(?int $id = null, ?string $nombre = null, ?string $file = null, ?string $descripcion = null, ?Tareas $tarea = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->file = $file;
        $this->descripcion = $descripcion;
        $this->tarea = $tarea;
    }

    /**
     * Establece el identificador único del fichero.
     *
     * @param int $id Identificador único del fichero.
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * Establece el nombre del fichero.
     *
     * @param string $nombre Nombre del fichero.
     */
    public function setNombre (string $nombre): void {
        $this->nombre = $nombre;
    }

    /**
     * Establece la ruta o nombre del archivo en el sistema.
     *
     * @param string $file Ruta o nombre del archivo en el sistema.
     */
    public function setFile (string $file): void {
        $this->file = $file;
    }


    /**
     * Establece la descripción del fichero.
     *
     * @param string $descripcion Descripción del fichero.
     */
    public function setDescripcion (string $descripcion): void {
        $this->descripcion = $descripcion;
    }


    /**
     * Establece la tarea asociada al fichero.
     *
     * @param Tareas $tarea Tarea asociada al fichero.
     */
    public function setTareas (Tareas $tarea): void {
        $this->tarea = $tarea;
    }

    // Getters
    /**
     * Obtiene el identificador único del fichero.
     *
     * @return int|null Identificador único del fichero.
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Obtiene el nombre del fichero.
     *
     * @return string|null Nombre del fichero.
     */
    public function getNombre(): ?string {
        return $this->nombre;
    }

    /**
     * Obtiene la ruta o nombre del archivo en el sistema.
     *
     * @return string|null Ruta o nombre del archivo en el sistema.
     */
    public function getFile(): ?string {
        return $this->file;
    }

    /**
     * Obtiene la descripción del fichero.
     *
     * @return string|null Descripción del fichero.
     */
    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    /**
     * Obtiene la tarea asociada al fichero.
     *
     * @return Tareas|null Tarea asociada al fichero.
     */
    public function getTareas(): ?Tareas {
        return $this->tarea;
    }

    /**
     * Valida los campos del formulario para introducir un nuevo fichero.
     *
     * @param string|null $nombre      Nombre del fichero.
     * @param string|null $descripcion Descripción del fichero.
     * @param array|null  $fichero     Información del fichero subido.
     *
     * @return array|bool Array con los errores encontrados o true si la validación es exitosa.
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
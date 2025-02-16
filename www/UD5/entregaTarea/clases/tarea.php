<?php

/**
 * Debe incluir las propiedades correspondientes ya existentes: id, titulo, descripcion, estado, usuario. 
 * Serán privados y tendrán sus getters y setters.
 */
class Tarea {

    // Propiedades
    private ?int $id;
    private ?string $titulo;
    private ?string $descripcion;
    private ?string $estado;
    private ?int $usuario;

    // Constructor
    public function __construct(?int $id = null, ?string $titulo = null, ?string $descripcion, ?string $estado, ?int $usuario){
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->usuario = $usuario;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }
    public function getTitulo(): string {
        return $this->titulo;
    }
    public function getDescripcion(): string {
        return $this->descripcion;
    }
    public function getEstado(): string {
        return $this->estado;
    }
    public function getUsuario(): int {
        return $this->usuario;
    }

    // Setters
    public function setId($id): void {
        $this->id = $id;
    }
    public function setTitulo($titulo): void {
        $this->titulo = $titulo;
    }
    public function setDescripcion($descripcion): void {
        $this->descripcion = $descripcion;
    }
    public function setEstado($estado): void {
        $this->estado = $estado;
    }
    public function setUsuario($usuario): void {
        $this->usuario = $usuario;
    }
}
?>
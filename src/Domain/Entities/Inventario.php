<?php
namespace Domain\Entities;

class Inventario {
    private ?int $id;
    private string $codigo;
    private string $nombre;
    private ?string $marca;
    private string $tipo;
    private ?string $descripcion;
    private int $cantidad;
    private int $idTaller;
    private int $status;

    public function __construct(
        ?int $id,
        string $codigo,
        string $nombre,
        ?string $marca,
        string $tipo,
        ?string $descripcion,
        int $cantidad,
        int $idTaller,
        int $status = 1
    ) {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->marca = $marca;
        $this->tipo = $tipo;
        $this->descripcion = $descripcion;
        $this->cantidad = $cantidad;
        $this->idTaller = $idTaller;
        $this->status = $status;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }
    public function getCodigo(): string {
        return $this->codigo;
    }
    public function getNombre(): string {
        return $this->nombre;
    }
    public function getMarca(): ?string {
        return $this->marca;
    }
    public function getTipo(): string {
        return $this->tipo;
    }
    public function getDescripcion(): ?string {
        return $this->descripcion;
    }
    public function getCantidad(): int {
        return $this->cantidad;
    }
    public function getIdTaller(): int {
        return $this->idTaller;
    }
    public function getStatus(): int {
        return $this->status;
    }
}
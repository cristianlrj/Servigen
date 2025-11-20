<?php
namespace Domain\Entities;

class PreRequisicion {
    private ?int $id;
    private int $umypf_n;
    private string $fecha_creacion;
    private string $estado;
    private array $items;

    public function __construct(
        ?int $id,
        int $umypf_n,
        string $fecha_creacion,
        string $estado,
        array $items = []
    ) {
        $this->id = $id;
        $this->umypf_n = $umypf_n;
        $this->fecha_creacion = $fecha_creacion;
        $this->estado = $estado;
        $this->items = $items;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getUmypfN(): int {
        return $this->umypf_n;
    }

    public function getFechaCreacion(): string {
        return $this->fecha_creacion;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getItems(): array {
        return $this->items;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUmypfN(int $umypf_n): void {
        $this->umypf_n = $umypf_n;
    }

    public function setFechaCreacion(string $fecha_creacion): void {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function setEstado(string $estado): void {
        $this->estado = $estado;
    }

    public function setItems(array $items): void {
        $this->items = $items;
    }

    public function addItem(array $item): void {
        $this->items[] = $item;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['umypf_n'],
            $data['fecha_creacion'] ?? date('Y-m-d H:i:s'),
            $data['estado'] ?? 'Pendiente',
            $data['items'] ?? []
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'umypf_n' => $this->umypf_n,
            'fecha_creacion' => $this->fecha_creacion,
            'estado' => $this->estado,
            'items' => $this->items,
        ];
    }
}
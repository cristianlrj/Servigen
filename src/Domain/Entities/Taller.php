<?php
namespace Domain\Entities;

class Taller {
    private ?int $id;
    private string $nombreTaller;
    private int $status;

    public function __construct(
        ?int $id = null,
        string $nombreTaller,
        int $status = 1
    ) {
        $this->id = $id;
        $this->nombreTaller = $nombreTaller;
        $this->status = $status;
    }

    public static function crearNuevo(
        string $nombreTaller,
    ): self {
        return new self(null, $nombreTaller, 1);
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNombreTaller(): string { return $this->nombreTaller; }
    public function getStatus(): int { return $this->status; }

    public function setNombreTaller(string $nombre): void {
        $this->nombreTaller = $nombre;
    }
    public function setStatus(int $status): void {
        $this->status = $status;
    }
}

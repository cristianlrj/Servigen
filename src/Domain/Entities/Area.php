<?php
namespace Domain\Entities;

class Area {
    private ?int $id;
    private string $nombre;
    private int $status;

    public function __construct(?int $id, string $nombre, int $status = 1) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->status = $status;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getStatus(): int {
        return $this->status;
    }

    public function setStatus(int $status): void {
        $this->status = $status;
    }
}
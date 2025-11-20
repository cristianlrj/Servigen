<?php
namespace Domain\Entities;
class Rol {
    private ?int $id;
    private string $nombreRol;

    public function __construct(
        ?int $id = null,
        string $nombreRol
    ) {
        $this->id = $id;
        $this->nombreRol = $nombreRol;
    }

    public static function crearNuevo(
        string $nombreRol,
    ): self {
        return new self(null, $nombreRol);
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNombreRol(): string { return $this->nombreRol; }
}

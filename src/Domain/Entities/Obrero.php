<?php 
namespace Domain\Entities;

class Obrero {
    public int $id;
    public string $nombre;
    public string $apellido;
    public string $cedula;
    public string $cargo;
    public ?string $habilidades;
    public ?string $taller = null;
    public ?string $area = null;

    public function __construct(
        int $id,
        string $nombre,
        string $apellido,
        string $cedula,
        string $cargo,
        ?string $habilidades = null,
        ?string $taller = null,
        ?string $area = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->cedula = $cedula;
        $this->cargo = $cargo;
        $this->taller = $taller;
        $this->habilidades = $habilidades;
        $this->area = $area;
    }

     public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellido(): string {
        return $this->apellido;
    }

    public function getCedula(): string {
        return $this->cedula;
    }

    public function getCargo(): string {
        return $this->cargo;
    }

    public function getTaller(): ?string {
        return $this->taller;
    }

    public function getHabilidades(): ?string {
        return $this->habilidades;
    }

    public function getArea(): ?string {
        return $this->area;
    }
}

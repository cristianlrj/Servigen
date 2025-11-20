<?php

namespace Domain\Entities;

class DescripcionFalla
{
    private int $umypf_n;
    private string $descripcion;
    private string $estado;
    private string $fecha;

    public function __construct(
        int $umypf_n,
        string $descripcion,
        string $estado,
        string $fecha
    ) {
        $this->umypf_n = $umypf_n;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->fecha = $fecha;
    }

    // Getters
    public function getUmypfN(): int
    {
        return $this->umypf_n;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }
}
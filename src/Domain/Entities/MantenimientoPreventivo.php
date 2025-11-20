<?php

namespace Domain\Entities;

class MantenimientoPreventivo {
    private $id;
    private $nombre_equipo;
    private $id_taller;
    private $id_usuario;
    private $tipo_mantenimiento;
    private $descripcion_tarea;
    private $fecha_programada;
    private $fecha_ejecucion;
    private $estado;
    private $observaciones;
    private $created_at;
    private $updated_at;

    public function __construct(
        ?int $id,
        string $nombre_equipo,
        int $id_taller,
        int $id_usuario,
        string $tipo_mantenimiento,
        string $descripcion_tarea,
        string $fecha_programada,
        ?string $fecha_ejecucion,
        string $estado,
        ?string $observaciones,
        ?string $created_at,
        ?string $updated_at
    ) {
        $this->id = $id;
        $this->nombre_equipo = $nombre_equipo;
        $this->id_taller = $id_taller;
        $this->id_usuario = $id_usuario;
        $this->tipo_mantenimiento = $tipo_mantenimiento;
        $this->descripcion_tarea = $descripcion_tarea;
        $this->fecha_programada = $fecha_programada;
        $this->fecha_ejecucion = $fecha_ejecucion;
        $this->estado = $estado;
        $this->observaciones = $observaciones;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNombreEquipo(): string {
        return $this->nombre_equipo;
    }

    public function getIdTaller(): int {
        return $this->id_taller;
    }

    public function getIdUsuario(): int {
        return $this->id_usuario;
    }

    public function getTipoMantenimiento(): string {
        return $this->tipo_mantenimiento;
    }

    public function getDescripcionTarea(): string {
        return $this->descripcion_tarea;
    }

    public function getFechaProgramada(): string {
        return $this->fecha_programada;
    }

    public function getFechaEjecucion(): ?string {
        return $this->fecha_ejecucion;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getObservaciones(): ?string {
        return $this->observaciones;
    }

    public function getCreatedAt(): ?string {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string {
        return $this->updated_at;
    }

    // Setters
    public function setNombreEquipo(string $nombre_equipo): void {
        $this->nombre_equipo = $nombre_equipo;
    }

    public function setIdTaller(int $id_taller): void {
        $this->id_taller = $id_taller;
    }

    public function setIdUsuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function setTipoMantenimiento(string $tipo_mantenimiento): void {
        $this->tipo_mantenimiento = $tipo_mantenimiento;
    }

    public function setDescripcionTarea(string $descripcion_tarea): void {
        $this->descripcion_tarea = $descripcion_tarea;
    }

    public function setFechaProgramada(string $fecha_programada): void {
        $this->fecha_programada = $fecha_programada;
    }

    public function setFechaEjecucion(?string $fecha_ejecucion): void {
        $this->fecha_ejecucion = $fecha_ejecucion;
    }

    public function setEstado(string $estado): void {
        $this->estado = $estado;
    }

    public function setObservaciones(?string $observaciones): void {
        $this->observaciones = $observaciones;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre_equipo' => $this->nombre_equipo,
            'id_taller' => $this->id_taller,
            'id_usuario' => $this->id_usuario,
            'tipo_mantenimiento' => $this->tipo_mantenimiento,
            'descripcion_tarea' => $this->descripcion_tarea,
            'fecha_programada' => $this->fecha_programada,
            'fecha_ejecucion' => $this->fecha_ejecucion,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
        ];
    }
}
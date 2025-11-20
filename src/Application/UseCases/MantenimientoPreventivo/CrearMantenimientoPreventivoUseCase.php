<?php

namespace Application\UseCases\MantenimientoPreventivo;

use Domain\Entities\MantenimientoPreventivo;
use Domain\Repositories\MantenimientoPreventivoRepositoryInterface;

class CrearMantenimientoPreventivoUseCase {
    private $repository;

    public function __construct(MantenimientoPreventivoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(
        string $nombre_equipo,
        int $id_taller,
        int $id_usuario,
        string $tipo_mantenimiento,
        string $descripcion_tarea,
        string $fecha_programada
    ): MantenimientoPreventivo {
        $mantenimiento = new MantenimientoPreventivo(
            null,
            $nombre_equipo,
            $id_taller,
            $id_usuario,
            $tipo_mantenimiento,
            $descripcion_tarea,
            $fecha_programada,
            null, // fecha_ejecucion
            'Programado', // estado
            null, // observaciones
            null, null // timestamps
        );

        return $this->repository->save($mantenimiento);
    }
}
<?php
namespace Application\UseCases\ReporteFallas;

use Domain\Entities\ReporteFallas;
use Domain\Repositories\ReporteFallasRepositoryInterface;

class RegistrarReporteFallasUseCase {
    private ReporteFallasRepositoryInterface $repository;

    public function __construct(ReporteFallasRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(
        int $idUsuario,
        int $idTaller,
        string $unidadSolicitante,
        string $personaContacto,
        ?string $emailContacto,
        string $descripcion,
    ): void {
        // La fecha de creación y el estado inicial se manejan dentro del repositorio o la entidad.
        // Aquí solo pasamos los datos que vienen del formulario/controlador.
        $reporteFallas = new ReporteFallas(
            0, // umypf_n se autoincrementa, 0 es un placeholder
            $idUsuario,
            date('Y-m-d H:i:s'), // Fecha actual
            $idTaller,
            $unidadSolicitante,
            $personaContacto,
            $emailContacto,
            $descripcion,
            'Pendiente', // Estado inicial
            date('Y-m-d H:i:s') // Fecha del último estado (inicialmente la misma que la creación)
        );

        $this->repository->save($reporteFallas);
    }
}

<?php
namespace Application\UseCases\ReporteFallas;

use Domain\Repositories\ReporteFallasRepositoryInterface;

class CargarSatisfaccionReporteFallasUseCase {
    private ReporteFallasRepositoryInterface $repository;

    public function __construct(ReporteFallasRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(
        int $id,
        int $satisfaccion,
        ?string $comentarios
    ): void {
        $reporteFallas = $this->repository->findById($id);
        
        if (!$reporteFallas) {
            throw new \Exception("Reporte de fallas no encontrado");
        }

        // Actualizar los campos de satisfacción en la entidad
        $reporteFallas->setSatisfaccion($satisfaccion);
        $reporteFallas->setComentariosSatisfaccion($comentarios);

        // Persistir los cambios en el repositorio
        $this->repository->updateSatisfaccion($reporteFallas);
    }
}

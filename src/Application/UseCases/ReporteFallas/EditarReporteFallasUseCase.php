<?php
namespace Application\UseCases\ReporteFallas;

use Domain\Entities\ReporteFallas;
use Domain\Repositories\ReporteFallasRepositoryInterface;

class EditarReporteFallasUseCase {
    private ReporteFallasRepositoryInterface $repository;

    public function __construct(ReporteFallasRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(
        int $id,
        string $descripcion,
        string $estado
    ): void {
        $reporteFallas = $this->repository->findById($id);
        
        if (!$reporteFallas) {
            throw new \Exception("Reporte de fallas no encontrado");
        }
        
        // En lugar de actualizar el objeto ReporteFallas y luego llamar a update,
        // directamente agregamos una nueva entrada de log.
        // La fecha se generará en el repositorio o se pasará aquí si es necesario.
        $this->repository->addLogEntry($id, $descripcion, $estado, date('Y-m-d H:i:s'));
    }
}

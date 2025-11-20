<?php
namespace Application\UseCases\ReporteFallas;

use Domain\Repositories\ReporteFallasRepositoryInterface;
use Domain\Entities\DescripcionFalla;

class GetHistorialReporteFallasUseCase {
    private ReporteFallasRepositoryInterface $repository;

    public function __construct(ReporteFallasRepositoryInterface $repository) {
        $this->repository = $repository;
    }
    
    public function ejecutar(int $umypf_n): array {
        return $this->repository->getHistorialForReporte($umypf_n);
    }
}

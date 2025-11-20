<?php
namespace Application\UseCases\ReporteFallas;

use Domain\Repositories\ReporteFallasRepositoryInterface;

class GetAllReporteFallasUseCase {
    private ReporteFallasRepositoryInterface $reporteFallasRepo;


    public function __construct(
        ReporteFallasRepositoryInterface $reporteFallasRepo
    ) {
        $this->reporteFallasRepo = $reporteFallasRepo;
    }

    public function ejecutar(): array {
        $fallas = $this->reporteFallasRepo->findAll();

        return $fallas;
    }
}
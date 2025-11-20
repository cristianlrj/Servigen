<?php
namespace Application\UseCases\ReporteFallas;

use Domain\Entities\ReporteFallas;
use Domain\Repositories\ReporteFallasRepositoryInterface;

class getReporteFallasUseCase {
    private ReporteFallasRepositoryInterface $repository;

    public function __construct(ReporteFallasRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): ?ReporteFallas {
        return $this->repository->findById($id);
    }
}

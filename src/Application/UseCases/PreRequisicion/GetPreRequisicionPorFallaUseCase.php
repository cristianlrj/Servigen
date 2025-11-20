<?php
namespace Application\UseCases\PreRequisicion;

use Domain\Entities\PreRequisicion;
use Domain\Repositories\PreRequisicionRepository;

class GetPreRequisicionPorFallaUseCase {
    private PreRequisicionRepository $repository;

    public function __construct(PreRequisicionRepository $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $umypf_n): ?PreRequisicion {
        return $this->repository->findByFallaId($umypf_n);
    }
}
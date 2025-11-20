<?php
namespace Application\UseCases\PreRequisicion;

use Domain\Repositories\PreRequisicionRepository;

class GetAllPreRequisicionesUseCase {
    private PreRequisicionRepository $repository;

    public function __construct(PreRequisicionRepository $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(): array {
        return $this->repository->findAll();
    }
}
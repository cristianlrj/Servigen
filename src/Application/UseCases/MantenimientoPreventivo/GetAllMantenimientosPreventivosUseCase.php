<?php

namespace Application\UseCases\MantenimientoPreventivo;

use Domain\Repositories\MantenimientoPreventivoRepositoryInterface;

class GetAllMantenimientosPreventivosUseCase {
    private $repository;

    public function __construct(MantenimientoPreventivoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(): array {
        return $this->repository->findAll();
    }
}
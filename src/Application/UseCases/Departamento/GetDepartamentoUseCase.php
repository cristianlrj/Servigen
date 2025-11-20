<?php
namespace Application\UseCases\Departamento;

use Domain\Repositories\DepartamentoRepositoryInterface;
use Domain\Entities\Departamento;

class GetDepartamentoUseCase {
    private DepartamentoRepositoryInterface $repository;

    public function __construct(DepartamentoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): ?Departamento {
        return $this->repository->findById($id);
    }
}
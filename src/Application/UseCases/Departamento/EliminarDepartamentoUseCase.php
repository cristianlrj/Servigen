<?php
namespace Application\UseCases\Departamento;

use Domain\Repositories\DepartamentoRepositoryInterface;

class EliminarDepartamentoUseCase {
    private $repository;

    public function __construct(DepartamentoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): void {
        $this->repository->delete($id);
    }
}
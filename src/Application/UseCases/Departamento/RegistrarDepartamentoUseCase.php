<?php
namespace Application\UseCases\Departamento;

use Domain\Repositories\DepartamentoRepositoryInterface;
use Domain\Entities\Departamento;

class RegistrarDepartamentoUseCase {
    private DepartamentoRepositoryInterface $repository;

    public function __construct(DepartamentoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(string $nombre): void {
        // El ID es null porque es autoincremental
        $departamento = new Departamento(null, $nombre);
        $this->repository->save($departamento);
    }
}
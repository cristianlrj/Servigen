<?php
namespace Application\UseCases\Departamento;

use Domain\Repositories\DepartamentoRepositoryInterface;
use Domain\Entities\Departamento;

class EditarDepartamentoUseCase {
    private DepartamentoRepositoryInterface $repository;

    public function __construct(DepartamentoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id, string $nombre): void {
        $departamento = new Departamento($id, $nombre);
        $this->repository->update($departamento);
    }
}
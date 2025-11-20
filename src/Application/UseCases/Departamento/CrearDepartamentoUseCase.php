<?php
namespace Application\UseCases\Departamento;

use Domain\Entities\Departamento;
use Domain\Repositories\DepartamentoRepositoryInterface;

class CrearDepartamentoUseCase {
    private $repository;

    public function __construct(DepartamentoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(string $nombre): void {
        $departamento = new Departamento(null, $nombre);
        $this->repository->save($departamento);
    }
}
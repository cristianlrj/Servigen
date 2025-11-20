<?php
namespace Application\UseCases\Departamento;

use Domain\Repositories\DepartamentoRepositoryInterface;

class ActualizarDepartamentoUseCase {
    private $repository;

    public function __construct(DepartamentoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id, string $nombre): void {
        $departamento = $this->repository->findById($id);
        if (!$departamento) {
            throw new \Exception("Departamento no encontrado.");
        }

        $departamento->setNombre($nombre);

        $this->repository->update($departamento);
    }
}
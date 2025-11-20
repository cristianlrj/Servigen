<?php
namespace Application\UseCases\Inventario;

use Domain\Repositories\InventarioRepositoryInterface;

class EliminarInventarioUseCase {
    private InventarioRepositoryInterface $repository;

    public function __construct(InventarioRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): void {
        $this->repository->delete($id);
    }
}
<?php
namespace Application\UseCases\Inventario;

use Domain\Repositories\InventarioRepositoryInterface;
use Domain\Entities\Inventario;

class GetInventarioUseCase {
    private InventarioRepositoryInterface $repository;

    public function __construct(InventarioRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): ?Inventario {
        return $this->repository->findById($id);
    }
}
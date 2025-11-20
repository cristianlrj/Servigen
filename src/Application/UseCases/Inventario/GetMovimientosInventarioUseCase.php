<?php
namespace Application\UseCases\Inventario;

use Domain\Repositories\InventarioRepositoryInterface;

class GetMovimientosInventarioUseCase {
    private InventarioRepositoryInterface $repository;

    public function __construct(InventarioRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id_inventario): array {
        return $this->repository->findMovimientosByInventarioId($id_inventario);
    }
}
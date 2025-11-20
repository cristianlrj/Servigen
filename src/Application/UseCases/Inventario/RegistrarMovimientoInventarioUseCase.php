<?php
namespace Application\UseCases\Inventario;

use Domain\Repositories\InventarioRepositoryInterface;
use Exception;

class RegistrarMovimientoInventarioUseCase {
    private InventarioRepositoryInterface $repository;

    public function __construct(InventarioRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id_inventario, int $cantidad, string $tipo_movimiento): void {
        if ($cantidad <= 0) {
            throw new Exception("La cantidad debe ser un número positivo.");
        }

        $this->repository->registrarMovimiento($id_inventario, $cantidad, $tipo_movimiento);
    }
}
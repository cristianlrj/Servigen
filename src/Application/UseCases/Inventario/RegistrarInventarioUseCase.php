<?php
namespace Application\UseCases\Inventario;

use Domain\Repositories\InventarioRepositoryInterface;
use Domain\Entities\Inventario;

class RegistrarInventarioUseCase {
    private InventarioRepositoryInterface $repository;

    public function __construct(InventarioRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(
        string $codigo,
        string $nombre,
        ?string $marca,
        string $tipo,
        ?string $descripcion,
        int $cantidad,
        int $idTaller
    ): void {
        $item = new Inventario(
            null, // ID es autoincremental
            $codigo, $nombre, $marca, $tipo, $descripcion, $cantidad, $idTaller
        );
        $this->repository->save($item);
    }
}
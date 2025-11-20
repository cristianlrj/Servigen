<?php
namespace Application\UseCases\Inventario;

use Domain\Repositories\InventarioRepositoryInterface;
use Domain\Entities\Inventario;

class EditarInventarioUseCase {
    private InventarioRepositoryInterface $repository;

    public function __construct(InventarioRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(
        int $id,
        string $codigo,
        string $nombre,
        ?string $marca,
        string $tipo,
        ?string $descripcion,
        int $idTaller
    ): void {
        $item = new Inventario(
            $id, $codigo, $nombre, $marca, $tipo, $descripcion, 0, $idTaller
        );
        $this->repository->update($item);
    }
}
<?php
namespace Application\UseCases\Area;

use Domain\Repositories\AreaRepositoryInterface;

class ActualizarAreaUseCase {
    private $repository;

    public function __construct(AreaRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id, string $nombre): void {
        $area = $this->repository->findById($id);
        if (!$area) {
            throw new \Exception("Área no encontrada.");
        }

        $area->setNombre($nombre);

        $this->repository->update($area);
    }
}
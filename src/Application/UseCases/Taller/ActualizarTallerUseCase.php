<?php
namespace Application\UseCases\Taller;

use Domain\Repositories\TallerRepositoryInterface;

class ActualizarTallerUseCase {
    private $repository;

    public function __construct(TallerRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id, string $nombre): void {
        $taller = $this->repository->findById($id);
        if (!$taller) {
            throw new \Exception("Taller no encontrado.");
        }

        $taller->setNombreTaller($nombre);

        $this->repository->update($taller);
    }
}
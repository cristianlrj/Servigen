<?php
namespace Application\UseCases\Taller;

use Domain\Entities\Taller;
use Domain\Repositories\TallerRepositoryInterface;

class CrearTallerUseCase {
    private $repository;

    public function __construct(TallerRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(string $nombre): void {
        $taller = new Taller(null, $nombre);
        $this->repository->save($taller);
    }
}
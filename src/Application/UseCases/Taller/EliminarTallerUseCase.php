<?php
namespace Application\UseCases\Taller;

use Domain\Repositories\TallerRepositoryInterface;

class EliminarTallerUseCase {
    private $repository;

    public function __construct(TallerRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): void {
        $this->repository->delete($id);
    }
}
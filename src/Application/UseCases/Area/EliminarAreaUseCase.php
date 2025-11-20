<?php
namespace Application\UseCases\Area;

use Domain\Repositories\AreaRepositoryInterface;

class EliminarAreaUseCase {
    private $repository;

    public function __construct(AreaRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): void {
        $this->repository->delete($id);
    }
}
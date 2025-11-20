<?php
namespace Application\UseCases\Area;

use Domain\Repositories\AreaRepositoryInterface;
use Domain\Entities\Area;

class GetAreaUseCase {
    private $repository;

    public function __construct(AreaRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): ?Area {
        return $this->repository->findById($id);
    }
}
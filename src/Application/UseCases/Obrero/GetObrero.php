<?php
namespace Application\UseCases\Obrero;

use Domain\Entities\Obrero;
use Domain\Repositories\ObreroRepositoryInterface;

class GetObrero {
    private ObreroRepositoryInterface $repository;

    public function __construct(ObreroRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $id): ?Obrero {
        return $this->repository->findById($id);
    }
}

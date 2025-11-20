<?php
namespace Application\UseCases\Obrero;

use Domain\Repositories\ObreroRepositoryInterface;

class GetAllObreros {
    private ObreroRepositoryInterface $repo;

    public function __construct(ObreroRepositoryInterface $repo) {
        $this->repo = $repo;
    }

    public function ejecutar(array $filters = []): array {
        return $this->repo->getAll($filters);
    }
}
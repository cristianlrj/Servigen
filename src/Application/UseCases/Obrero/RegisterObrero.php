<?php
namespace Application\UseCases\Obrero;

use Domain\Repositories\ObreroRepositoryInterface;
use Domain\Entities\Obrero;

class RegisterObrero {
    private ObreroRepositoryInterface $repo;

    public function __construct(ObreroRepositoryInterface $repo) {
        $this->repo = $repo;
    }

    public function ejecutar(Obrero $obrero): void {
        $this->repo->save($obrero);
    }
}
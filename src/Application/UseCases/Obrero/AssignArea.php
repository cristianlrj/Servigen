<?php
namespace Application\UseCases\Obrero;

use Domain\Repositories\ObreroRepositoryInterface;

class AssignArea {
    private ObreroRepositoryInterface $repo;

    public function __construct(ObreroRepositoryInterface $repo) {
        $this->repo = $repo;
    } 

    public function ejecutar(int $obreroId, string $area): void {
        $this->repo->assignArea($obreroId, $area);
    }
}
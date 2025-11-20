<?php
namespace Application\UseCases\Area;

use Domain\Entities\Area;
use Domain\Repositories\AreaRepositoryInterface;

class CrearAreaUseCase {
    private $repository;

    public function __construct(AreaRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(string $nombre): void {
        $area = new Area(null, $nombre);
        $this->repository->save($area);
    }
}
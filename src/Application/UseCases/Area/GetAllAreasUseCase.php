<?php
namespace Application\UseCases\Area;

use Domain\Repositories\AreaRepositoryInterface;

class GetAllAreasUseCase {
    private $repository;

    public function __construct(AreaRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(): array {
        return $this->repository->findAll();
    }
}
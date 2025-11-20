<?php
namespace Application\UseCases\Taller;

use Domain\Repositories\TallerRepositoryInterface;

class getAllTalleresUseCase {
    private TallerRepositoryInterface $repositorio;

    public function __construct(TallerRepositoryInterface $repositorio) {
        $this->repositorio = $repositorio;
    }

    public function ejecutar(): array {
        return $this->repositorio->findAll();
    }
}

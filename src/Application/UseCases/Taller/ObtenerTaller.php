<?php
namespace Application\UseCases\Taller;

use Domain\Repositories\TallerRepositoryInterface;

class ObtenerTaller {
    private TallerRepositoryInterface $repositorio;

    public function __construct(TallerRepositoryInterface $repositorio) {
        $this->repositorio = $repositorio;
    }

    public function ejecutar(int $id) {
        return $this->repositorio->findById($id);
    }
}

<?php
namespace Application\UseCases\PreRequisicion;

use Domain\Entities\PreRequisicion;
use Domain\Repositories\PreRequisicionRepository;

class CrearPreRequisicionUseCase {
    private PreRequisicionRepository $repository;

    public function __construct(PreRequisicionRepository $repository) {
        $this->repository = $repository;
    }

    public function ejecutar(int $umypf_n, array $items): PreRequisicion {
        if (empty($items)) {
            throw new \InvalidArgumentException("La lista de materiales no puede estar vacía.");
        }

        $preRequisicion = PreRequisicion::fromArray([
            'umypf_n' => $umypf_n,
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'estado' => 'Pendiente',
            'items' => $items
        ]);

        return $this->repository->save($preRequisicion);
    }
}
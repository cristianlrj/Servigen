<?php
namespace Application\UseCases\Departamento;

use Domain\Repositories\DepartamentoRepositoryInterface;

class ObtenerDepartamentosUseCase {
    private DepartamentoRepositoryInterface $departamentoRepository;

    public function __construct(DepartamentoRepositoryInterface $departamentoRepository) {
        $this->departamentoRepository = $departamentoRepository;
    }

    public function ejecutar(): array {
        return $this->departamentoRepository->findAll();
    }
}

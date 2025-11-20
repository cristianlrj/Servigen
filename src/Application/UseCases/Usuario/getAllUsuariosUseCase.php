<?php
namespace Application\UseCases\Usuario;

use Domain\Repositories\UsuarioRepositoryInterface;

class getAllUsuariosUseCase {
    private UsuarioRepositoryInterface $repositorio;

    public function __construct(UsuarioRepositoryInterface $repositorio) {
        $this->repositorio = $repositorio;
    }

    public function ejecutar(): array {
        return $this->repositorio->getAll();
    }
}

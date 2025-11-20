<?php
namespace Application\UseCases\Usuario;

use Domain\Repositories\UsuarioRepositoryInterface;

class getUsuarioUseCase {
    private UsuarioRepositoryInterface $repositorio;
    public function __construct(UsuarioRepositoryInterface $repositorio) {
        $this->repositorio = $repositorio;
    }
    public function ejecutar($id) {
        return $this->repositorio->obtenerUsuarioPorId($id);
    }
}

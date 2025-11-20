<?php
namespace Application\UseCases\Auth;

use Domain\Repositories\UsuarioRepositoryInterface;
use Domain\Entities\Usuario;
use Exception;

class LoginUseCase {
    private UsuarioRepositoryInterface $repositorio;

    public function __construct(UsuarioRepositoryInterface $repositorio) {
        $this->repositorio = $repositorio;
    }

    public function ejecutar(string $nombreUsuario, string $password): Usuario {
        $usuario = $this->repositorio->buscarPorNombreUsuario($nombreUsuario);

        if (!$usuario || $password !== $usuario->getHashedPassword()) {
            throw new Exception("Credenciales inválidas.");
        }

        return $usuario;
    }
}

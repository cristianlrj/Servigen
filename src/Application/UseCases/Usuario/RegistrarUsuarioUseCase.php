<?php

namespace Application\UseCases\Usuario;

use Domain\Repositories\UsuarioRepositoryInterface;
use Domain\Entities\Usuario;
use Exception;

class RegistrarUsuarioUseCase {
    private UsuarioRepositoryInterface $repositorio;

    public function __construct(UsuarioRepositoryInterface $repositorio) {
        $this->repositorio = $repositorio;
    }

    public function ejecutar(string $nombre, string $apellido, string $nombreUsuario, string $email, string $password, int $rolId, ?int $tallerId = null): Usuario {
        if ($this->repositorio->buscarPorNombreUsuario($nombreUsuario)) {
            throw new Exception("El nombre de usuario ya está en uso.");
        }

        $usuario = Usuario::crearNuevo($nombre, $apellido, $nombreUsuario, $email, $password, $rolId, $tallerId);
        return $this->repositorio->guardar($usuario);
    }
}

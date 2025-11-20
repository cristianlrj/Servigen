<?php

namespace Application\UseCases\Usuario;

use Domain\Repositories\UsuarioRepositoryInterface;
use Domain\Entities\Usuario;
use Exception;

class EditarUsuarioUseCase {
    private UsuarioRepositoryInterface $repositorio;

    public function __construct(UsuarioRepositoryInterface $repositorio) {
        $this->repositorio = $repositorio;
    }

    public function ejecutar(int $id, string $nombre, string $apellido, string $nombreUsuario, string $email, string $password, int $rolId, ?int $tallerId = null): Usuario {
        $usuario = $this->repositorio->obtenerUsuarioPorId($id);
        if (!$usuario) {
            throw new Exception("Usuario no encontrado.");
        }
        if($password == '') {
            $password = $usuario->getHashedPassword();
        }
        $usuario->actualizar($nombre, $apellido, $nombreUsuario, $email, $password, $rolId, $tallerId);
        return $this->repositorio->editar($usuario);
    }
}

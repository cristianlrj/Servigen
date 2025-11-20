<?php

namespace Application\UseCases\Usuario;

use Domain\Repositories\UsuarioRepositoryInterface;
use Exception;

class EliminarUsuarioUseCase {
    private UsuarioRepositoryInterface $repositorio;

    public function __construct(UsuarioRepositoryInterface $repositorio) {
        $this->repositorio = $repositorio;
    }

    /**
     * Ejecuta el caso de uso para eliminar un usuario (borrado físico).
     *
     * @param int $id El ID del usuario a eliminar.
     * @return bool True si la eliminación fue exitosa.
     * @throws Exception Si el usuario no se encuentra.
     */
    public function ejecutar(int $id): bool {
        // 1. (Opcional pero recomendado) Verificar que el usuario existe
        $usuario = $this->repositorio->obtenerUsuarioPorId($id);
        if (!$usuario) {
            throw new Exception("Usuario no encontrado.");
        }

        // 2. Llamar a un nuevo método "eliminar" en el repositorio
        // (Necesitarás añadir este método a tu UsuarioRepositoryInterface)
        return $this->repositorio->eliminar($id);
    }
}
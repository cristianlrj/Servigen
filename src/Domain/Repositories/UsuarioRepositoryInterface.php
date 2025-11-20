<?php
namespace Domain\Repositories;

use Domain\Entities\Usuario;

interface UsuarioRepositoryInterface {
    public function buscarPorNombreUsuario(string $nombreUsuario): ?Usuario;
    public function guardar(Usuario $usuario): Usuario;
    public function getAll(): array;
    public function obtenerUsuarioPorId(int $id): ?Usuario;
    public function editar(Usuario $usuario): Usuario;
    public function eliminar(int $id): bool;
}

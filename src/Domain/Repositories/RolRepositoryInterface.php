<?php
namespace Domain\Repositories;

use Domain\Entities\Rol;

interface RolRepositoryInterface {
    public function buscarPorId(int $id): ?Rol;
    public function obtenerTodos(): array;
}
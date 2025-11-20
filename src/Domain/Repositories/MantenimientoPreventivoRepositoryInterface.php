<?php

namespace Domain\Repositories;

use Domain\Entities\MantenimientoPreventivo;

interface MantenimientoPreventivoRepositoryInterface {
    public function findById(int $id): ?MantenimientoPreventivo;
    public function findAll(): array;
    public function save(MantenimientoPreventivo $mantenimiento): MantenimientoPreventivo;
    public function update(MantenimientoPreventivo $mantenimiento): MantenimientoPreventivo;
    public function delete(int $id): bool;
}
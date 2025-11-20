<?php
namespace Domain\Repositories;

use Domain\Entities\Area;

interface AreaRepositoryInterface {
    public function save(Area $area): void;
    public function update(Area $area): void;
    public function findById(int $id): ?Area;
    public function findAll(): array;
    public function delete(int $id): void;
}
<?php
namespace Domain\Repositories;

use Domain\Entities\Taller;

interface TallerRepositoryInterface {
    public function save(Taller $taller): void;
    public function update(Taller $taller): void;
    public function findById(int $id): ?Taller;
    public function findAll(): array;
    public function delete(int $id): void;
}
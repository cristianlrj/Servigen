<?php
namespace Domain\Repositories;

use Domain\Entities\Obrero;

interface ObreroRepositoryInterface {
    public function save(Obrero $obrero): void;

    public function findByCedula(string $cedula): ?Obrero;
    
    public function findById(int $int): ?Obrero;

    public function assignArea(
                            int $id, 
                            string $area): void;

    public function getAll(array $filters = []): array;

    public function countAll(): int;
    
}
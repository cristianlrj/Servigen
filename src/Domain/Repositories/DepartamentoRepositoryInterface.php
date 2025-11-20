<?php
namespace Domain\Repositories;

use Domain\Entities\Departamento;

interface DepartamentoRepositoryInterface {
    public function save(Departamento $departamento): void;
    public function update(Departamento $departamento): void;
    public function findById(int $id): ?Departamento;
    public function findAll(): array;
    public function delete(int $id): void;
}
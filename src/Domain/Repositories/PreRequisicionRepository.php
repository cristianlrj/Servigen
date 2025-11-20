<?php
namespace Domain\Repositories;

use Domain\Entities\PreRequisicion;

interface PreRequisicionRepository {
    public function save(PreRequisicion $preRequisicion): PreRequisicion;
    public function findByFallaId(int $umypf_n): ?PreRequisicion;
    public function findAll(): array;
}
<?php
namespace Domain\Repositories;

use Domain\Entities\ReporteFallas;

interface ReporteFallasRepositoryInterface {

    public function save(ReporteFallas $reporteFallas): void;

    public function update(ReporteFallas $reporteFallas): void;

    public function addLogEntry(int $umypf_n, string $descripcion, string $estado, string $fecha): void;

    public function findById(int $id): ?ReporteFallas;

    public function findAll(): array;

    public function delete(int $id): void;

    public function findByUsuarioId(int $usuarioId): array;

    public function findByEstado(string $estado): array;

    public function getDashboardStats(): array;

    public function getTareasRecientes(int $limit = 5): array;

    public function getHistorialForReporte(int $umypf_n): array;

    public function updateSatisfaccion(ReporteFallas $reporteFallas): void;
}

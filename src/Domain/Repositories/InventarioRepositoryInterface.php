<?php
namespace Domain\Repositories;

use Domain\Entities\Inventario;

interface InventarioRepositoryInterface {
    public function getAll(): array;
    public function findById(int $id): ?Inventario;
    public function save(Inventario $item): void;
    public function update(Inventario $item): void;
    public function delete(int $id): void;

    /**
     * Registra un movimiento de entrada o salida para un artículo del inventario.
     *
     * @param integer $id_inventario
     * @param integer $cantidad
     * @param string $tipo_movimiento ('entrada' o 'salida')
     * @return void
     */
    public function registrarMovimiento(int $id_inventario, int $cantidad, string $tipo_movimiento): void;

    /**
     * Busca todos los movimientos de inventario para un artículo específico.
     *
     * @param integer $id_inventario
     * @return array
     */
    public function findMovimientosByInventarioId(int $id_inventario): array;
}
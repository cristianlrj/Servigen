<?php
namespace Infrastructure\Persistence\Adapter;

use Domain\Entities\Inventario;
use Domain\Repositories\InventarioRepositoryInterface;
use PDO;

class MysqlInventarioRepository extends MysqlPersistenceAdapter implements InventarioRepositoryInterface {

    private function mapRowToEntity(array $row): Inventario {
        return new Inventario(
            $row['id_inventario'],
            $row['codigo'],
            $row['nombre'],
            $row['marca'],
            $row['tipo'],
            $row['descripcion'],
            $row['stock'],
            $row['id_taller'],
            $row['status']
        );
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM inventario WHERE status = 1 ORDER BY nombre ASC");
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $this->mapRowToEntity($row);
        }
        return $items;
    }

    public function findById(int $id): ?Inventario {
        $stmt = $this->conn->prepare("SELECT * FROM inventario WHERE id_inventario = :id AND status = 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function save(Inventario $item): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO inventario (codigo, nombre, marca, tipo, descripcion, stock, id_taller, id_usuario, status) 
             VALUES (:codigo, :nombre, :marca, :tipo, :descripcion, :cantidad, :id_taller, :id_usuario, 1)"
        );
        $stmt->execute([
            ':codigo' => $item->getCodigo(),
            ':nombre' => $item->getNombre(),
            ':marca' => $item->getMarca(),
            ':tipo' => $item->getTipo(),
            ':id_usuario' => $_SESSION['usuario_id'], // Asumiendo que el ID del usuario está en la sesión
            ':descripcion' => $item->getDescripcion(),
            ':cantidad' => $item->getCantidad(),
            ':id_taller' => $item->getIdTaller()
        ]);
    }

    public function update(Inventario $item): void {
        $stmt = $this->conn->prepare(
            "UPDATE inventario SET 
                codigo = :codigo, nombre = :nombre, marca = :marca, tipo = :tipo, 
                descripcion = :descripcion, id_taller = :id_taller
             WHERE id_inventario = :id"
        );
        $stmt->execute([
            ':id' => $item->getId(),
            ':codigo' => $item->getCodigo(),
            ':nombre' => $item->getNombre(),
            ':marca' => $item->getMarca(),
            ':tipo' => $item->getTipo(),
            ':descripcion' => $item->getDescripcion(),
            ':id_taller' => $item->getIdTaller()
        ]);
    }

    public function registrarMovimiento(int $id_inventario, int $cantidad, string $tipo_movimiento): void {
        $this->conn->beginTransaction();
        try {
            // 1. Obtener el artículo y bloquear la fila para la actualización
            $stmt = $this->conn->prepare("SELECT stock FROM inventario WHERE id_inventario = :id FOR UPDATE");
            $stmt->execute([':id' => $id_inventario]);
            $articulo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$articulo) {
                throw new Exception("Artículo de inventario no encontrado.");
            }

            $stock_actual = $articulo['stock'];

            // 2. Calcular nuevo stock
            if ($tipo_movimiento === 'entrada') {
                $nuevo_stock = $stock_actual + $cantidad;
            } elseif ($tipo_movimiento === 'salida') {
                if ($stock_actual < $cantidad) {
                    throw new Exception("Stock insuficiente para realizar la salida.");
                }
                $nuevo_stock = $stock_actual - $cantidad;
            } else {
                throw new Exception("Tipo de movimiento no válido.");
            }

            // 3. Actualizar el stock en la tabla de inventario
            $stmtUpdate = $this->conn->prepare("UPDATE inventario SET stock = :cantidad WHERE id_inventario = :id");
            $stmtUpdate->execute([':cantidad' => $nuevo_stock, ':id' => $id_inventario]);

            // 4. Insertar el registro del movimiento
            $stmtMov = $this->conn->prepare(
                "INSERT INTO movimiento_inventario (id_inventario, id_usuario, cantidad, tipo_movimiento, fecha_movimiento) 
                 VALUES (:id_inventario, :id_usuario, :cantidad, :tipo_movimiento, NOW())"
            );
            $stmtMov->execute([
                ':id_inventario' => $id_inventario,
                ':id_usuario' => $_SESSION['usuario_id'],
                ':cantidad' => $cantidad,
                ':tipo_movimiento' => $tipo_movimiento
            ]);

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e; // Re-lanzar la excepción para que sea manejada por el caso de uso/controlador
        }
    }

    public function findMovimientosByInventarioId(int $id_inventario): array {
        $stmt = $this->conn->prepare("SELECT * FROM movimiento_inventario WHERE id_inventario = :id ORDER BY fecha_movimiento DESC");
        $stmt->execute([':id' => $id_inventario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): void {
        $stmt = $this->conn->prepare("UPDATE inventario SET status = 0 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
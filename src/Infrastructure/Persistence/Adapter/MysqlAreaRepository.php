<?php
namespace Infrastructure\Persistence\Adapter;

use Domain\Entities\Area;
use Domain\Repositories\AreaRepositoryInterface;
use PDO;

class MysqlAreaRepository extends MysqlPersistenceAdapter implements AreaRepositoryInterface {

    public function save(Area $area): void {
        $stmt = $this->conn->prepare("INSERT INTO areas (nombre, status) VALUES (:nombre, 1)");
        $stmt->execute([':nombre' => $area->getNombre()]);
    }

    public function update(Area $area): void {
        $stmt = $this->conn->prepare("UPDATE areas SET nombre = :nombre WHERE id = :id");
        $stmt->execute([
            ':nombre' => $area->getNombre(),
            ':id' => $area->getId()
        ]);
    }

    public function findById(int $id): ?Area {
        $stmt = $this->conn->prepare("SELECT * FROM areas WHERE id_area = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Area($row['id_area'], $row['nombre'], $row['status']);
    }

    public function findAll(): array {
        $stmt = $this->conn->query("SELECT * FROM areas WHERE status = 1 ORDER BY nombre ASC");
        $areas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $areas[] = new Area($row['id_area'], $row['nombre'], $row['status']);
        }
        return $areas;
    }

    public function delete(int $id): void {
        // Soft delete: cambiar el estado a 0 (inactivo)
        $stmt = $this->conn->prepare("UPDATE areas SET status = 0 WHERE id_area = :id");
        $stmt->execute([':id' => $id]);
    }
}
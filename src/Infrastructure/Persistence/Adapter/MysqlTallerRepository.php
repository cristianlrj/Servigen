<?php
namespace Infrastructure\Persistence\Adapter;

use Domain\Entities\Taller;
use Domain\Repositories\TallerRepositoryInterface;
use PDO;

class MysqlTallerRepository extends MysqlPersistenceAdapter implements TallerRepositoryInterface {

    public function save(Taller $taller): void {
        $stmt = $this->conn->prepare("INSERT INTO taller (especialidad, status) VALUES (:nombre, 1)");
        $stmt->execute([':nombre' => $taller->getNombreTaller()]);
    }

    public function update(Taller $taller): void {
        $stmt = $this->conn->prepare("UPDATE taller SET especialidad = :nombre WHERE id_taller = :id");
        $stmt->execute([
            ':nombre' => $taller->getNombreTaller(),
            ':id' => $taller->getId()
        ]);
    }

    public function findById(int $id): ?Taller {
        $stmt = $this->conn->prepare("SELECT * FROM taller WHERE id_taller = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Taller($row['id_taller'], $row['especialidad'], $row['status']);
    }

    public function findAll(): array {
        $stmt = $this->conn->query("SELECT * FROM taller WHERE status = 1 ORDER BY especialidad ASC");
        $talleres = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $talleres[] = new Taller($row['id_taller'], $row['especialidad'], $row['status']);
        }
        return $talleres;
    }

    public function delete(int $id): void {
        // Soft delete: cambiar el estado a 0 (inactivo)
        $stmt = $this->conn->prepare("UPDATE taller SET status = 0 WHERE id_taller = :id");
        $stmt->execute([':id' => $id]);
    }
}
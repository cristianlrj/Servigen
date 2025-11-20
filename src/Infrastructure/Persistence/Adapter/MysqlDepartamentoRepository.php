<?php
namespace Infrastructure\Persistence\Adapter;

use Domain\Entities\Departamento;
use Domain\Repositories\DepartamentoRepositoryInterface;
use PDO;

class MysqlDepartamentoRepository extends MysqlPersistenceAdapter implements DepartamentoRepositoryInterface {

    public function save(Departamento $departamento): void {
        $stmt = $this->conn->prepare("INSERT INTO departamento (nombre, status) VALUES (:nombre, 1)");
        $stmt->execute([':nombre' => $departamento->getNombre()]);
    }

    public function update(Departamento $departamento): void {
        $stmt = $this->conn->prepare("UPDATE departamento SET nombre = :nombre WHERE id_departamento = :id");
        $stmt->execute([
            ':nombre' => $departamento->getNombre(),
            ':id' => $departamento->getId()
        ]);
    }

    public function findById(int $id): ?Departamento {
        $stmt = $this->conn->prepare("SELECT * FROM departamento WHERE id_departamento = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Departamento($row['id_departamento'], $row['nombre'], $row['status']);
    }

    public function findAll(): array {
        $stmt = $this->conn->query("SELECT * FROM departamento WHERE status = 1 ORDER BY nombre ASC");
        $departamentos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $departamentos[] = new Departamento($row['id_departamento'], $row['nombre'], $row['status']);
        }
        return $departamentos;
    }

    public function delete(int $id): void {
        // Soft delete: cambiar el estado a 0 (inactivo)
        $stmt = $this->conn->prepare("UPDATE departamento SET status = 0 WHERE id_departamento = :id");
        $stmt->execute([':id' => $id]);
    }
}
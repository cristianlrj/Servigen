<?php 
namespace Infrastructure\Persistence\Adapter;

use Domain\Repositories\RolRepositoryInterface;
use Domain\Entities\Rol;
use PDO;

class MysqlRolRepository extends MysqlPersistenceAdapter implements RolRepositoryInterface {

    public function buscarPorId(int $id): ?Rol {
        $stmt = $this->conn->prepare("SELECT * FROM rol WHERE id_rol = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Rol((int)$row['id_rol'], $row['nombre']);
    }

    public function obtenerTodos(): array {
        $stmt = $this->conn->prepare("SELECT * FROM rol");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($row) {
            return new Rol((int)$row['id_rol'], $row['nombre']);
        }, $rows);
    }
}
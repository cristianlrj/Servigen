<?php 
namespace Infrastructure\Persistence\Adapter;

use Domain\Repositories\ObreroRepositoryInterface;
use Domain\Entities\Obrero;
use PDO;

class MysqlObreroRepository extends MysqlPersistenceAdapter implements ObreroRepositoryInterface {
    public function save(Obrero $obrero): void {
        $stmt = $this->conn->prepare("INSERT INTO obreros (nombre, cedula, cargo) VALUES (?, ?, ?)");
        $stmt->execute([$obrero->nombre, $obrero->cedula, $obrero->cargo]);
    }

    public function findByCedula(string $cedula): ?Obrero {
        $stmt = $this->conn->prepare("SELECT * FROM obreros WHERE cedula = ?");
        $stmt->execute([$cedula]);
        return $stmt->fetchObject(Obrero::class) ?: null;
    }

    public function findById(int $id): ?Obrero {
        $stmt = $this->conn->prepare("SELECT o.id_obreros, o.nombres, o.apellidos, o.cedula_obrero, o.ocupacion, o.habilidades, da.id_taller AS taller, da.id_area AS area 
                FROM obreros o
                LEFT JOIN divisiones_area da ON o.id_obreros = da.id_obrero
                WHERE id_obreros = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Obrero($row['id_obreros'], $row['nombres'], $row['apellidos'], $row['cedula_obrero'], $row['ocupacion'], $row['habilidades'], $row['taller'], $row['area']);
    }

    public function assignArea(int $id, string $area): void {
        $stmt = $this->conn->prepare("UPDATE divisiones_area SET id_area = ? WHERE id_obrero = ?");
        $stmt->execute([$area, $id]);
    }

    public function getAll(array $filters = []): array {
        $sql = "SELECT o.id_obreros, o.nombres, o.apellidos, o.cedula_obrero, o.ocupacion, o.habilidades, da.id_taller AS taller, da.id_area AS area 
                FROM obreros o
                LEFT JOIN divisiones_area da ON o.id_obreros = da.id_obrero";
        $params = [];
        if (!empty($filters)) {
            $clauses = [];
            foreach ($filters as $key => $val) {
                // Ajustar los nombres de las columnas si los filtros vienen de la entidad Obrero
                // y no directamente de la tabla 'obreros' o 'divisiones_area'
                $clauses[] = "o.$key = ?"; 
                $params[] = $val;
            }
            $sql .= " WHERE " . implode(" AND ", $clauses);
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($row) {
            return new Obrero($row['id_obreros'], $row['nombres'], $row['apellidos'], $row['cedula_obrero'], $row['ocupacion'], $row['habilidades'], $row['taller'], $row['area']);
        }, $rows);
    }

    public function countAll(): int {
        // Asumo que 'obrero' es la tabla y que solo contamos los activos
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM obreros");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$resultado['total'];
    }
}
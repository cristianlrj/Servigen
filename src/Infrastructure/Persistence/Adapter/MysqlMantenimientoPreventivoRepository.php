<?php

namespace Infrastructure\Persistence\Adapter;

use Domain\Entities\MantenimientoPreventivo;
use Domain\Repositories\MantenimientoPreventivoRepositoryInterface;
use Infrastructure\Persistence\Adapter\MysqlBaseRepository;
use PDO;

class MysqlMantenimientoPreventivoRepository extends MysqlPersistenceAdapter implements MantenimientoPreventivoRepositoryInterface {

    public function findById(int $id): ?MantenimientoPreventivo {
        $stmt = $this->conn->prepare("SELECT * FROM mantenimientos_preventivos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapRowToMantenimiento($row);
    }

    public function findAll(): array {
        $stmt = $this->conn->query("SELECT * FROM mantenimientos_preventivos ORDER BY fecha_programada DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $mantenimientos = [];
        foreach ($rows as $row) {
            $mantenimientos[] = $this->mapRowToMantenimiento($row);
        }
        return $mantenimientos;
    }

    public function save(MantenimientoPreventivo $mantenimiento): MantenimientoPreventivo {
        $sql = "INSERT INTO mantenimientos_preventivos (nombre_equipo, id_taller, id_usuario, tipo_mantenimiento, descripcion_tarea, fecha_programada, estado) 
                VALUES (:nombre_equipo, :id_taller, :id_usuario, :tipo_mantenimiento, :descripcion_tarea, :fecha_programada, :estado)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':nombre_equipo' => $mantenimiento->getNombreEquipo(),
            ':id_taller' => $mantenimiento->getIdTaller(),
            ':id_usuario' => $mantenimiento->getIdUsuario(),
            ':tipo_mantenimiento' => $mantenimiento->getTipoMantenimiento(),
            ':descripcion_tarea' => $mantenimiento->getDescripcionTarea(),
            ':fecha_programada' => $mantenimiento->getFechaProgramada(),
            ':estado' => $mantenimiento->getEstado()
        ]);
        
        $stmt->execute();
        $id = $this->conn->lastInsertId();
        return $this->findById($id);
    }

    public function update(MantenimientoPreventivo $mantenimiento): MantenimientoPreventivo {
        // Implementar lógica de actualización si es necesario
        return $mantenimiento;
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM mantenimientos_preventivos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->execute();
    }

    private function mapRowToMantenimiento(array $row): MantenimientoPreventivo {
        return new MantenimientoPreventivo(
            (int)$row['id'],
            $row['nombre_equipo'],
            (int)$row['id_taller'],
            (int)$row['id_usuario'],
            $row['tipo_mantenimiento'],
            $row['descripcion_tarea'],
            $row['fecha_programada'],
            $row['fecha_ejecucion'],
            $row['estado'],
            $row['observaciones'],
            $row['created_at'],
            $row['updated_at']
        );
    }
}
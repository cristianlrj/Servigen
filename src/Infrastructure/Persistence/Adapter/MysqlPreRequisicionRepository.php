<?php
namespace Infrastructure\Persistence\Adapter;

use Domain\Entities\PreRequisicion;
use Domain\Repositories\PreRequisicionRepository;
use Infrastructure\Persistence\Asapter;

class MysqlPreRequisicionRepository extends MysqlPersistenceAdapter implements PreRequisicionRepository {

    public function save(PreRequisicion $preRequisicion): PreRequisicion { 
        $pdo = $this->conn;
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO pre_requisiciones (umypf_n, fecha_creacion, estado) VALUES (?, ?, ?)"
            );
            $stmt->execute([
                $preRequisicion->getUmypfN(),
                $preRequisicion->getFechaCreacion(),
                $preRequisicion->getEstado()
            ]);
            $idPreRequisicion = $pdo->lastInsertId();
            $preRequisicion->setId($idPreRequisicion);

            $stmtItem = $pdo->prepare(
                "INSERT INTO pre_requisicion_items (id_pre_requisicion, id_articulo, cantidad) VALUES (?, ?, ?)"
            );
            foreach ($preRequisicion->getItems() as $item) {
                $stmtItem->execute([$idPreRequisicion, $item['id_articulo'], $item['cantidad']]);
            }

            $pdo->commit();
            return $preRequisicion;
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function findByFallaId(int $umypf_n): ?PreRequisicion {
        $pdo = $this->conn;
        $stmt = $pdo->prepare("SELECT * FROM pre_requisiciones WHERE umypf_n = ?");
        $stmt->execute([$umypf_n]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $stmtItems = $pdo->prepare("SELECT id_articulo, cantidad FROM pre_requisicion_items WHERE id_pre_requisicion = ?");
        $stmtItems->execute([$data['id']]);
        $items = $stmtItems->fetchAll(\PDO::FETCH_ASSOC);
        $data['items'] = $items;

        return PreRequisicion::fromArray($data);
    }

    public function findAll(): array {
        $pdo = $this->conn;
        $sql = "
            SELECT 
                *
            FROM prerequisiciones pr
            ORDER BY pr.fecha DESC
        ";
        $stmt = $pdo->query($sql);
        $preRequisicionesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $preRequisiciones = [];
        // foreach ($preRequisicionesData as $data) {
        //     $stmtItems = $pdo->prepare("SELECT id_articulo, cantidad FROM pre_requisicion_items WHERE id_pre_requisicion = ?");
        //     $stmtItems->execute([$data['id']]);
        //     $items = $stmtItems->fetchAll(\PDO::FETCH_ASSOC);
        //     $data['items'] = $items;

        //     // Añadimos el id_taller al array de datos para que la entidad lo pueda usar si es necesario.
        //     // Aunque la entidad PreRequisicion no tiene id_taller, lo guardamos para el futuro.
        //     $preRequisiciones[] = PreRequisicion::fromArray($data);
        // }
        return $preRequisiciones;
    }
}
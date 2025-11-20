<?php
namespace Infrastructure\Persistence\Adapter;

use Domain\Entities\ReporteFallas;
use Domain\Repositories\ReporteFallasRepositoryInterface;
use Domain\Entities\DescripcionFalla;
use PDO;

class MysqlReporteFallasRepository extends MysqlPersistenceAdapter implements ReporteFallasRepositoryInterface {
   

    public function save(ReporteFallas $reporteFallas): void {
        try {
            $this->conn->beginTransaction();

            $sqlMaster = "INSERT INTO reporte_fallas (id_usuario, fecha_creacion, id_taller, id_departamento, persona_contacto, email_contacto) 
                          VALUES (:id_usuario, :fecha_creacion, :id_taller, :id_departamento, :persona_contacto, :email_contacto)";
            
            $stmtMaster = $this->conn->prepare($sqlMaster);
            $stmtMaster->execute([
                ':id_usuario' => $reporteFallas->getUsuarioId(),
                ':fecha_creacion' => $reporteFallas->getFechaCreacion(),
                ':id_taller' => $reporteFallas->getIdTaller(),
                ':id_departamento' => $reporteFallas->getUnidadSolicitante(),
                ':persona_contacto' => $reporteFallas->getPersonaContacto(),
                ':email_contacto' => $reporteFallas->getEmailContacto()
            ]);


            $lastId = $this->conn->lastInsertId();


            $sqlDetail = "INSERT INTO descripcion_falla (umypf_n, descripcion, estado, fecha) 
                          VALUES (:umypf_n, :descripcion, :estado, :fecha)";
            
            $stmtDetail = $this->conn->prepare($sqlDetail);
            $stmtDetail->execute([
                ':umypf_n' => $lastId,
                ':descripcion' => $reporteFallas->getDescripcion(), // La descripción inicial
                ':estado' => 'Pendiente', // El estado inicial
                ':fecha' => $reporteFallas->getFechaCreacion() // La misma fecha de creación
            ]);

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            // Manejar o lanzar la excepción
            throw new Exception("Error al guardar la falla: " . $e->getMessage());
        }
    }

    /**
     * Agrega una nueva entrada al log de una falla existente (ej. cambio de estado).
     * Esta es la implementación "correcta" de un "update" en tu modelo de log.
     */
    public function addLogEntry(int $umypf_n, string $descripcion, string $estado, string $fecha): void {
        $sql = "INSERT INTO descripcion_falla (umypf_n, descripcion, estado, fecha) 
                VALUES (:umypf_n, :descripcion, :estado, :fecha)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':umypf_n' => $umypf_n,
            ':descripcion' => $descripcion,
            ':estado' => $estado,
            ':fecha' => $fecha // Generalmente NOW() o la fecha del formulario
        ]);
    }

    /**
     * Método 'update' original. 
     * En este diseño, 'update' no debería cambiar el estado ni la descripción.
     * Solo debería cambiar datos del MAESTRO (ej. reasignar a otro taller).
     * Si 'update' DEBE cambiar el estado, entonces debe llamar a addLogEntry.
     *
     * Aquí un ejemplo de 'update' que solo actualiza el maestro:
     */
    public function update(ReporteFallas $reporteFallas): void {
         $sql = "UPDATE reporte_fallas SET 
                    id_taller = :id_taller, 
                    id_departamento = :id_departamento, 
                    persona_contacto = :persona_contacto
                    -- Añadir otras columnas del MAESTRO que puedan cambiar
                 WHERE umypf_n = :umypf_n";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_taller' => $reporteFallas->getIdTaller(),
            ':id_departamento' => $reporteFallas->getUnidadSolicitante(),
            ':persona_contacto' => $reporteFallas->getPersonaContacto(),
            ':umypf_n' => $reporteFallas->getId() // Asumo que getId() devuelve umypf_n
        ]);
        
        // Si tu 'update' TAMBIÉN debe registrar un cambio de estado/descripción:
        // $this->addLogEntry(
        //     $reporteFallas->getId(),
        //     $reporteFallas->getDescripcion(), // Esta sería la "descripción del cambio"
        //     $reporteFallas->getEstado(), // El nuevo estado
        //     date('Y-m-d H:i:s')
        // );
    }

    /**
     * Función base para todas las consultas FIND.
     * Obtiene el ESTADO ACTUAL de las fallas.
     */
    private function getBaseFindQuery(string $extraWhere = ""): string {
        return "
            SELECT 
                t1.umypf_n, t1.id_usuario, t1.fecha_creacion, t1.id_taller, t1.id_departamento, t1.persona_contacto, t1.email_contacto,
                t1.satisfaccion, t1.comentarios_satisfaccion,
                (SELECT df_initial.descripcion FROM descripcion_falla df_initial WHERE df_initial.umypf_n = t1.umypf_n ORDER BY df_initial.fecha ASC LIMIT 1) as descripcion_inicial,
                (SELECT df_initial.estado FROM descripcion_falla df_initial WHERE df_initial.umypf_n = t1.umypf_n ORDER BY df_initial.fecha ASC LIMIT 1) as estado_inicial,
                t2.descripcion, t2.estado, t2.fecha as fecha_ultimo_estado
            FROM 
                reporte_fallas t1
            INNER JOIN (
                -- Subconsulta para obtener SÓLO la última entrada de log de cada falla
                SELECT 
                    df.*,
                    ROW_NUMBER() OVER(PARTITION BY df.umypf_n ORDER BY df.fecha DESC) as rn
                FROM descripcion_falla df
            ) t2 ON t1.umypf_n = t2.umypf_n
            WHERE 
                t2.rn = 1 -- Filtramos para obtener solo la más reciente
                $extraWhere
        ";
    }

    /**
     * Mapea una fila de la BD a la entidad ReporteFallas.
     * DEBES actualizar tu entidad para que coincida con esto.
     */
    private function mapRowToEntity(array $row): ReporteFallas {
        // Esta es la parte crítica. Tu entidad 'ReporteFallas'
        // debe poder aceptar todos estos datos del maestro Y del último log.
        
        // Asumo un constructor (¡probablemente tengas que crearlo!)
        $reporteFallas =new ReporteFallas(
            $row['umypf_n'],
            $row['id_usuario'],
            $row['fecha_creacion'],
            $row['id_taller'],
            $row['id_departamento'],
            $row['persona_contacto'],
            $row['email_contacto'],
            $row['descripcion_inicial'], // Descripción inicial
            $row['estado'], // Estado actual (del último log)
            $row['fecha_ultimo_estado'] // Fecha del último estado
        );
        $reporteFallas->setSatisfaccion($row['satisfaccion']);
        $reporteFallas->setComentariosSatisfaccion($row['comentarios_satisfaccion']);

        return $reporteFallas;

    }

    public function findById(int $id): ?ReporteFallas {
        $sql = $this->getBaseFindQuery("AND t1.umypf_n = :id");
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function findAll(): array {
        $sql = $this->getBaseFindQuery("ORDER BY t1.fecha_creacion DESC");
        $stmt = $this->conn->query($sql);
        
        $reportes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reportes[] = $this->mapRowToEntity($row);
        }
        
        return $reportes;
    }

    public function findByUsuarioId(int $usuarioId): array {
        $sql = $this->getBaseFindQuery("AND t1.id_usuario = :usuario_id ORDER BY t1.fecha_creacion DESC");
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        
        $reportes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reportes[] = $this->mapRowToEntity($row);
        }
        
        return $reportes;
    }

    public function findByEstado(string $estado): array {
        // NOTA: Esta consulta es más cara porque filtra por el estado *actual*
        $sql = $this->getBaseFindQuery("AND t2.estado = :estado ORDER BY t1.fecha_creacion DESC");
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':estado' => $estado]);
        
        $reportes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reportes[] = $this->mapRowToEntity($row);
        }
        
        return $reportes;
    }

    public function delete(int $id): void {
        // Con este diseño, debes borrar de ambas tablas.
        // O mejor, configurar ON DELETE CASCADE en tu base de datos.
        try {
            $this->conn->beginTransaction();

            // 1. Borrar de la tabla de log
            $sqlDetail = "DELETE FROM descripcion_falla WHERE umypf_n = :id";
            $stmtDetail = $this->conn->prepare($sqlDetail);
            $stmtDetail->execute([':id' => $id]);

            // 2. Borrar de la tabla maestra
            $sqlMaster = "DELETE FROM reporte_fallas WHERE umypf_n = :id";
            $stmtMaster = $this->conn->prepare($sqlMaster);
            $stmtMaster->execute([':id' => $id]);

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Error al eliminar la falla: " . $e->getMessage());
        }
    }

        public function getDashboardStats(): array {
        
        $sql = "
            -- CTE 1: 'EventTimestamps' pivota las fechas de los eventos
            WITH EventTimestamps AS (
                SELECT
                    t2.umypf_n,
                    t2.satisfaccion,
    
                    -- La fecha de creación del maestro es nuestro 'Inicio' (Pendiente)
                    t2.fecha_creacion AS fecha_pendiente, 
                    
                    -- Buscamos la PRIMERA vez que se marcó 'En Proceso'
                    MIN(CASE WHEN t1.estado = 'En Proceso' THEN t1.fecha ELSE NULL END) as fecha_en_proceso,
                    
                    -- Buscamos la PRIMERA vez que se marcó 'Finalizada'
                    MIN(CASE WHEN t1.estado = 'Finalizada' THEN t1.fecha ELSE NULL END) as fecha_solucion
                FROM 
                    reporte_fallas as t2
                LEFT JOIN 
                    descripcion_falla as t1 ON t1.umypf_n = t2.umypf_n
                WHERE 
                    MONTH(t2.fecha_creacion) = MONTH(CURDATE())
                    AND YEAR(t2.fecha_creacion) = YEAR(CURDATE())
                GROUP BY
                    t2.umypf_n, t2.fecha_creacion
            ),
            
            -- CTE 2: 'LatestStatus' encuentra el estado MÁS RECIENTE de cada falla
            LatestStatus AS (
                SELECT
                    t1.umypf_n,
                    t1.estado,
                    -- Asignamos un ranking a cada entrada por falla, ordenado por fecha DESC
                    ROW_NUMBER() OVER(PARTITION BY t1.umypf_n ORDER BY t1.fecha DESC) as rn
                FROM 
                    descripcion_falla as t1
                JOIN 
                    reporte_fallas as t2 ON t1.umypf_n = t2.umypf_n
                WHERE 
                    MONTH(t2.fecha_creacion) = MONTH(CURDATE())
                    AND YEAR(t2.fecha_creacion) = YEAR(CURDATE())
            ),
            
            -- CTE 3: 'CurrentStates' filtra solo el estado más reciente (rn = 1)
            CurrentStates AS (
                SELECT estado FROM LatestStatus WHERE rn = 1
            )
            
            -- Consulta Final: Combinamos los resultados de los CTEs
            SELECT
                -- Obtenemos los CONTEOS desde 'CurrentStates'
                (SELECT COUNT(*) FROM CurrentStates WHERE estado = 'Pendiente') as fallas_pendientes,
                (SELECT COUNT(*) FROM CurrentStates WHERE estado = 'En Proceso') as fallas_en_proceso,
                (SELECT COUNT(*) FROM CurrentStates WHERE estado = 'Finalizada') as fallas_solucionadas,
                
                -- Obtenemos los PROMEDIOS desde 'EventTimestamps'
                -- Promedio Aceptación: (Fecha 'En Proceso') - (Fecha 'Pendiente')
                AVG(TIMESTAMPDIFF(SECOND, fecha_pendiente, fecha_en_proceso)) as avg_aceptacion_segundos,
                
                -- Promedio Solución: (Fecha 'Finalizada') - (Fecha 'En Proceso')
                AVG(TIMESTAMPDIFF(SECOND, fecha_en_proceso, fecha_solucion)) as avg_solucion_segundos,

                AVG(CASE WHEN satisfaccion != 0 THEN satisfaccion ELSE NULL END) as avg_satisfaccion
            
            FROM 
                EventTimestamps
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        // fetch() está bien porque la consulta SIEMPRE devuelve 1 fila de totales
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Obtiene las tareas recientes.
     * Busca la fecha de 'Finalizada', pero obtiene la descripción
     * original de 'Pendiente' para usarla como título.
     */
    public function getTareasRecientes(int $limit = 5): array {
        $sql = "
            SELECT 
                -- Subconsulta para obtener la descripción de la falla original
                (SELECT 
                    d.descripcion 
                 FROM descripcion_falla d 
                 WHERE d.umypf_n = t1.umypf_n 
                 ORDER BY d.fecha ASC 
                 LIMIT 1
                ) as titulo,
                 (SELECT 
                    r.satisfaccion 
                 FROM reporte_fallas r
                 INNER JOIN descripcion_falla d ON r.umypf_n = d.umypf_n
                 WHERE r.umypf_n = t1.umypf_n 
                 ORDER BY d.fecha ASC 
                 LIMIT 1
                ) as satisfaccion,
                
                DATE_FORMAT(t1.fecha, '%d-%m-%Y') as fecha
            FROM 
                descripcion_falla as t1
            WHERE 
                t1.estado = 'Finalizada' -- Buscamos las que están finalizadas
            GROUP BY 
                t1.umypf_n -- Agrupamos por falla
            ORDER BY 
                t1.fecha DESC -- Las más recientes primero
            LIMIT :limit
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistorialForReporte(int $umypf_n): array {
        $sql = "SELECT umypf_n, descripcion, estado, fecha FROM descripcion_falla WHERE umypf_n = :umypf_n ORDER BY fecha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':umypf_n' => $umypf_n]);

        $historial = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Asumiendo que tienes una entidad DescripcionFalla
            // Si no, necesitarías crearla o adaptar cómo se manejan estos datos.
            $historial[] = new DescripcionFalla(
                $row['umypf_n'],
                $row['descripcion'],
                $row['estado'],
                $row['fecha']
            );
        }

        return $historial;
    }

    public function updateSatisfaccion(ReporteFallas $reporteFallas): void {
        $sql = "UPDATE reporte_fallas SET
                    satisfaccion = :satisfaccion,
                    comentarios_satisfaccion = :comentarios_satisfaccion
                 WHERE umypf_n = :umypf_n";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':satisfaccion' => $reporteFallas->getSatisfaccion(),
            ':comentarios_satisfaccion' => $reporteFallas->getComentariosSatisfaccion(),
            ':umypf_n' => $reporteFallas->getId()
        ]);

        // Una vez guardada la satisfacción, se crea una descripción en descripcion_falla
        // con la fecha en la que se completa la encuesta y la descripción es "Encuesta rellenada por el cliente"
        $this->addLogEntry(
            $reporteFallas->getId(),
            "Encuesta rellenada por el cliente",
            "Finalizada", // Asumimos que la encuesta solo se puede rellenar si está Finalizada
            date('Y-m-d H:i:s') // Fecha actual de cuando se rellena la encuesta
        );

    }

    public function guardarTokenSatisfaccion(int $reporteId, string $token, string $expiresAt): void {
        $sql = "UPDATE reporte_fallas SET
                    satisfaccion_token = :token,
                    satisfaccion_token_expiracion = :expires_at
                 WHERE umypf_n = :reporte_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':token' => $token,
            ':expires_at' => $expiresAt,
            ':reporte_id' => $reporteId
        ]);
    }

    public function validarTokenSatisfaccion(string $token): ?int {
        $sql = "SELECT umypf_n FROM reporte_fallas 
                WHERE satisfaccion_token = :token
                  AND satisfaccion_token_expiracion > NOW()";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':token' => $token
        ]);

        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        return $result ? (int)$result : null;
    }
}

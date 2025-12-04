<?php
namespace Application\UseCases\Dashboard;

use Domain\Repositories\MantenimientoPreventivoRepositoryInterface;
use Domain\Repositories\ReporteFallasRepositoryInterface;

class GetDashboardStatsUseCase {
    private MantenimientoPreventivoRepositoryInterface $mantenimientoRepo;
    private ReporteFallasRepositoryInterface $reporteFallasRepo;

    public function __construct(
        MantenimientoPreventivoRepositoryInterface $mantenimientoRepo, 
        ReporteFallasRepositoryInterface $reporteFallasRepo
    ) {
        $this->mantenimientoRepo = $mantenimientoRepo;
        $this->reporteFallasRepo = $reporteFallasRepo;
    }

    public function ejecutar(?int $month = null, ?int $year = null): array {
        // 1. Obtener estadísticas de fallas (esto es 1 sola consulta a la BD)
        $stats = $this->reporteFallasRepo->getDashboardStats($month, $year);

        // 2. Obtener total de mantenimientos
        $mantenimientos = $this->mantenimientoRepo->findAll();
        $totalMantenimientos = count($mantenimientos);

        // 3. Obtener tareas recientes
        $tareasRecientes = $this->reporteFallasRepo->getTareasRecientes(5);

        // 4. Formatear y combinar los datos
        $totalFallas = ($stats['fallas_pendientes'] ?? 0) + 
                       ($stats['fallas_en_proceso'] ?? 0) + 
                       ($stats['fallas_solucionadas'] ?? 0);

        return [
            'total_mantenimientos' => $totalMantenimientos,
            'fallas_pendientes' => (int)($stats['fallas_pendientes'] ?? 0),
            'fallas_en_proceso' => (int)($stats['fallas_en_proceso'] ?? 0),
            'fallas_solucionadas' => (int)($stats['fallas_solucionadas'] ?? 0),
            'total_fallas' => $totalFallas,
            
            // Formatear los tiempos promedio
            'tiempo_promedio_aceptacion' => $this->formatSegundos($stats['avg_aceptacion_segundos'] ?? 0),
            'tiempo_promedio_solucion' => $this->formatSegundos($stats['avg_solucion_segundos'] ?? 0),
            'promedio_satisfaccion' => round(($stats['avg_satisfaccion'] ?? 0) / 5 * 100, 1),

            // Raw values and Status for Traffic Lights (Semáforos)
            'raw_tiempo_aceptacion' => $stats['avg_aceptacion_segundos'] ?? 0,
            'status_aceptacion' => $this->getStatusTime($stats['avg_aceptacion_segundos'] ?? 0, 7200), // Meta: 2 horas (7200s)

            'raw_tiempo_solucion' => $stats['avg_solucion_segundos'] ?? 0,
            'status_solucion' => $this->getStatusTime($stats['avg_solucion_segundos'] ?? 0, 172800), // Meta: 2 días (172800s)

            'raw_satisfaccion' => round(($stats['avg_satisfaccion'] ?? 0) / 5 * 100, 1),
            'status_satisfaccion' => $this->getStatusSatisfaction(round(($stats['avg_satisfaccion'] ?? 0) / 5 * 100, 1), 90), // Meta: 90%
            
            'tareas_recientes' => $tareasRecientes,
        ];
    }

    private function getStatusTime(float $value, float $goal): string {
        if ($value == 0) return 'secondary'; // No data
        if ($value <= $goal) return 'success';
        if ($value <= $goal * 1.5) return 'warning';
        return 'danger';
    }

    private function getStatusSatisfaction(float $value, float $goal): string {
        if ($value == 0) return 'secondary'; // No data
        if ($value > 80) return 'success'; // Tolerancia: > 80% es verde
        if ($value >= $goal * 0.8) return 'warning'; // 80% of goal
        return 'danger';
    }

    /**
     * Helper para convertir segundos en un formato legible (Días, Horas, Minutos).
     */
    private function formatSegundos(float $segundos): string {
        if ($segundos == 0) return "N/A";

        $dias = floor($segundos / (3600 * 24));
        $segundos_restantes = $segundos % (3600 * 24);
        $horas = floor($segundos_restantes / 3600);
        $segundos_restantes %= 3600;
        $minutos = floor($segundos_restantes / 60);

        if ($dias >= 1) {
            return round($dias + ($horas / 24), 1) . " Días";
        }
        if ($horas >= 1) {
            return round($horas + ($minutos / 60), 1) . " Horas";
        }
        return (int)$minutos . " Minutos";
    }
}
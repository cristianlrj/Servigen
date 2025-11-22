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
            
            'tareas_recientes' => $tareasRecientes,
        ];
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
<?php
namespace Application\UseCases\Dashboard;

use Domain\Repositories\ObreroRepositoryInterface;
use Domain\Repositories\ReporteFallasRepositoryInterface;

class GetDashboardStatsUseCase {
    private ObreroRepositoryInterface $obreroRepo;
    private ReporteFallasRepositoryInterface $reporteFallasRepo;

    public function __construct(
        ObreroRepositoryInterface $obreroRepo, 
        ReporteFallasRepositoryInterface $reporteFallasRepo
    ) {
        $this->obreroRepo = $obreroRepo;
        $this->reporteFallasRepo = $reporteFallasRepo;
    }

    public function ejecutar(): array {
        // 1. Obtener estadísticas de fallas (esto es 1 sola consulta a la BD)
        $stats = $this->reporteFallasRepo->getDashboardStats();

        // 2. Obtener total de obreros
        $totalObreros = $this->obreroRepo->countAll();

        // 3. Obtener tareas recientes
        $tareasRecientes = $this->reporteFallasRepo->getTareasRecientes(5);

        // 4. Formatear y combinar los datos
        $totalFallas = ($stats['fallas_pendientes'] ?? 0) + 
                       ($stats['fallas_en_proceso'] ?? 0) + 
                       ($stats['fallas_solucionadas'] ?? 0);

        return [
            'total_obreros' => $totalObreros,
            'fallas_pendientes' => (int)($stats['fallas_pendientes'] ?? 0),
            'fallas_en_proceso' => (int)($stats['fallas_en_proceso'] ?? 0),
            'fallas_solucionadas' => (int)($stats['fallas_solucionadas'] ?? 0),
            'total_fallas' => $totalFallas,
            
            // Formatear los tiempos promedio
            'tiempo_promedio_aceptacion' => $this->formatSegundos($stats['avg_aceptacion_segundos'] ?? 0),
            'tiempo_promedio_solucion' => $this->formatSegundos($stats['avg_solucion_segundos'] ?? 0),
            'promedio_satisfaccion' => round($stats['avg_satisfaccion'] ?? 0, 1),
            
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
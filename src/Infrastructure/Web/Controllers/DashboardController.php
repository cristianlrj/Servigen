<?php 
namespace Infrastructure\Web\Controllers;

// Importar todas las clases que vamos a usar
use Application\UseCases\Dashboard\GetDashboardStatsUseCase;
use Infrastructure\Persistence\Adapter\MysqlMantenimientoPreventivoRepository;
use Infrastructure\Persistence\Adapter\MysqlReporteFallasRepository;

class DashboardController extends BaseController {

    public function index() {
        
        // 1. Instanciar los repositorios (ya tienes la conexión en tu BaseController/Adapter)
        $mantenimientoRepo = new MysqlMantenimientoPreventivoRepository();
        $reporteFallasRepo = new MysqlReporteFallasRepository();

        // 2. Instanciar el Caso de Uso e inyectar los repos
        $getDashboardStats = new GetDashboardStatsUseCase(
            $mantenimientoRepo,
            $reporteFallasRepo
        );

        // 3. Obtener filtros de la petición
        $month = isset($_GET['month']) ? $_GET['month'] : date('n');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        // Si el mes es 'all', pasamos null para obtener todos los tiempos
        if ($month === 'all') {
            $monthParam = null;
            $yearParam = null;
        } else {
            $monthParam = (int)$month;
            $yearParam = (int)$year;
        }

        // 4. Ejecutar el caso de uso
        $stats = $getDashboardStats->ejecutar($monthParam, $yearParam);

        // 5. Asignar los datos a la vista
        $this->data['title'] = "Servicio General";
        $this->data['selected_month'] = $month;
        $this->data['selected_year'] = $year;
        
        // Usamos array_merge para combinar los datos base con los datos dinámicos
        $this->data = array_merge($this->data, $stats);
        
        $data = $this->data;

        include __DIR__ . '/../Views/dashboard/home.php';
    }
}
?>
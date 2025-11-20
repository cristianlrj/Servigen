<?php 
namespace Infrastructure\Web\Controllers;

// Importar todas las clases que vamos a usar
use Application\UseCases\Dashboard\GetDashboardStatsUseCase;
use Infrastructure\Persistence\Adapter\MysqlObreroRepository;
use Infrastructure\Persistence\Adapter\MysqlReporteFallasRepository;

class DashboardController extends BaseController {

    public function index() {
        
        // 1. Instanciar los repositorios (ya tienes la conexión en tu BaseController/Adapter)
        $obreroRepo = new MysqlObreroRepository();
        $reporteFallasRepo = new MysqlReporteFallasRepository();

        // 2. Instanciar el Caso de Uso e inyectar los repos
        $getDashboardStats = new GetDashboardStatsUseCase(
            $obreroRepo,
            $reporteFallasRepo
        );

        // 3. Ejecutar el caso de uso
        $stats = $getDashboardStats->ejecutar();

        // 4. Asignar los datos a la vista
        $this->data['title'] = "Servicio General";
        
        // Usamos array_merge para combinar los datos base con los datos dinámicos
        $this->data = array_merge($this->data, $stats);
        
        $data = $this->data;

        include __DIR__ . '/../Views/dashboard/home.php';
    }
}
?>
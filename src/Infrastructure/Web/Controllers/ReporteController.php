<?php 

namespace Infrastructure\Web\Controllers;

use Mpdf\Mpdf;

//ReporteFallas use
use Application\UseCases\ReporteFallas\GetReporteFallasUseCase;
use Application\UseCases\ReporteFallas\GetAllReporteFallasUseCase;
use Application\UseCases\ReporteFallas\GetHistorialReporteFallasUseCase;
use Infrastructure\Persistence\Adapter\MysqlReporteFallasRepository;

use Application\UseCases\Usuario\getUsuarioUseCase;
use Infrastructure\Persistence\Adapter\MysqlUsuarioRepository;

use Application\UseCases\Taller\ObtenerTaller;
use Infrastructure\Persistence\Adapter\MysqlTallerRepository;

use Application\UseCases\Departamento\GetDepartamentoUseCase;
use Infrastructure\Persistence\Adapter\MysqlDepartamentoRepository;

//Obrero use
use Application\UseCases\Obrero\GetAllObreros;
use Infrastructure\Persistence\Adapter\MysqlObreroRepository;
use Application\UseCases\Area\GetAreaUseCase;
use Infrastructure\Persistence\Adapter\MysqlAreaRepository;

//Inventario use
use Application\UseCases\Inventario\GetAllInventarioUseCase;
use Infrastructure\Persistence\Adapter\MysqlInventarioRepository;


class ReporteController extends BaseController {

    public function index() {
        
        $this->data['title'] = "Servicio General";
        
        $this->data = array_merge($this->data);
        
        $data = $this->data;

        include __DIR__ . '/../Views/dashboard/home.php';
    }

    public function reporteFallasPdf($params = null){
        $mpdf = new Mpdf();

        
        ob_start();
        
        $reporteFallasRepo = new MysqlReporteFallasRepository();

        $getAllReporteFallas = new GetAllReporteFallasUseCase($reporteFallasRepo);

        $usuarioRepo = new MysqlUsuarioRepository();
        $getUsuarioUseCase = new getUsuarioUseCase($usuarioRepo);

        $tallerRpero = new MysqlTallerRepository();
        $getTallerUseCase = new ObtenerTaller($tallerRpero);

        $departamentoRepo = new MysqlDepartamentoRepository();
        $getDepartamentoUseCase = new GetDepartamentoUseCase($departamentoRepo);

        $tallerId = null;
        $estado = null;

        if ($params !== null) {
            $paramArray = explode(',', $params);
            if (isset($paramArray[0]) && $paramArray[0] !== 'null') {
                $tallerId = (int)$paramArray[0];
            }
            if (isset($paramArray[1]) && $paramArray[1] !== 'null') {
                $estado = $paramArray[1];
            }
        }

        $reportes = $getAllReporteFallas->ejecutar();

        // Filtrar los reportes si se proporcionaron tallerId o estado
        $reportesFiltrados = array_filter($reportes, function($reporte) use ($tallerId, $estado) {
            $matchTaller = ($tallerId === null || $reporte->getIdTaller() == $tallerId);
            $matchEstado = ($estado === null || $reporte->getEstado() == $estado);
            return $matchTaller && $matchEstado;
        });

        $reportes = $reportesFiltrados;
        
        $titulo = "Reporte de fallas";
        if ($tallerId !== null && $estado !== null) {
            $taller = $getTallerUseCase->ejecutar($tallerId);
            $titulo .= " - Taller: " . ($taller ? $taller->getNombreTaller() : 'Desconocido') . " | Estado: " . $estado;
        } elseif ($tallerId !== null) {
            $taller = $getTallerUseCase->ejecutar($tallerId);
            $titulo .= " - Taller: " . ($taller ? $taller->getNombreTaller() : 'Desconocido');
        } elseif ($estado !== null) {
            $titulo .= " - Estado: " . $estado;
        }
        include __DIR__ . '/../PDF/reporteFallas.php';

        // Asegúrate de que $getUsuarioUseCase y $getTallerUseCase estén disponibles en el scope del include
        // Esto ya se hace al pasarlos como variables al include.


        $html = ob_get_clean();

        $stylesheet = file_get_contents(__DIR__ . '/../PDF/pdf.css');

        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        $mpdf->WriteHTML($html);
        $mpdf->Output("reporteFallas.pdf", "I");
    }

    public function reporteFallasIndividualPdf($id){
        $mpdf = new Mpdf();

        ob_start();

        $reporteFallasRepo = new MysqlReporteFallasRepository();
        $getReporteFallaUseCase = new GetReporteFallasUseCase($reporteFallasRepo);

        $usuarioRepo = new MysqlUsuarioRepository();
        $getUsuarioUseCase = new getUsuarioUseCase($usuarioRepo);

        $tallerRepo = new MysqlTallerRepository();
        $getTallerUseCase = new ObtenerTaller($tallerRepo);

         $departamentoRepo = new MysqlDepartamentoRepository();
        $getDepartamentoUseCase = new GetDepartamentoUseCase($departamentoRepo);

        $reporte = $getReporteFallaUseCase->ejecutar($id);

        if (!$reporte) {
            // Manejar el caso de que el reporte no exista
            // Podrías redirigir o mostrar un error
            echo "Reporte de falla no encontrado.";
            return;
        }

        // Obtener el historial de la falla usando el caso de uso y asignarlo a la entidad
        $getHistorialReporteFallasUseCase = new GetHistorialReporteFallasUseCase($reporteFallasRepo);
        $historial = $getHistorialReporteFallasUseCase->ejecutar($id);
        
        $reporte->setHistorial($historial);

        include __DIR__ . '/../PDF/reporteFallaIndividual.php';
        $html = ob_get_clean();
        $stylesheet = file_get_contents(__DIR__ . '/../PDF/pdf.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html);
        $mpdf->Output("reporteFalla_".$id.".pdf", "I");
    }

    public function reporteCulminacionPdf($id){
        $mpdf = new Mpdf();

        ob_start();

        $reporteFallasRepo = new MysqlReporteFallasRepository();
        $getReporteFallaUseCase = new GetReporteFallasUseCase($reporteFallasRepo);

        $usuarioRepo = new MysqlUsuarioRepository();
        $getUsuarioUseCase = new getUsuarioUseCase($usuarioRepo);

        $tallerRepo = new MysqlTallerRepository();
        $getTallerUseCase = new ObtenerTaller($tallerRepo);

        $reporte = $getReporteFallaUseCase->ejecutar($id);

        if (!$reporte) {
            // Manejar el caso de que el reporte no exista
            // Podrías redirigir o mostrar un error
            echo "Reporte de falla no encontrado.";
            return;
        }

        $getHistorialReporteFallasUseCase = new GetHistorialReporteFallasUseCase($reporteFallasRepo);
        $historial = $getHistorialReporteFallasUseCase->ejecutar($id);
        
        $reporte->setHistorial($historial);

        // No se obtiene el historial para este reporte

        include __DIR__ . '/../PDF/reporteFallasCulminacion.php';
        $html = ob_get_clean();
        $stylesheet = file_get_contents(__DIR__ . '/../PDF/pdf.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html);
        $mpdf->Output("reporteCulminacion_".$id.".pdf", "I");
    } // "I" para previsualizar

    public function reporteObrerosPdf($tallerId = null) {
        $mpdf = new Mpdf();

        ob_start();

        $obreroRepo = new MysqlObreroRepository();
        $getAllObrerosUseCase = new GetAllObreros($obreroRepo);

        $tallerRepo = new MysqlTallerRepository();
        $getTallerUseCase = new ObtenerTaller($tallerRepo);

        $areaRepo = new MysqlAreaRepository();
        $getAreaUseCase = new GetAreaUseCase($areaRepo);

        $obreros = $getAllObrerosUseCase->ejecutar();

        $titulo = "Reporte de Obreros";

        if ($tallerId !== null && $tallerId !== 'null') {
            $tallerId = (int)$tallerId;
            $taller = $getTallerUseCase->ejecutar($tallerId);
            if ($taller) {
                $titulo .= " - Taller: " . $taller->getNombreTaller();
            }

            $obreros = array_filter($obreros, function($obrero) use ($tallerId) {
                return $obrero->getTaller() == $tallerId;
            });
        }

        // Para obtener el nombre del taller en la tabla
        $talleres = (new \Application\UseCases\Taller\GetAllTalleresUseCase($tallerRepo))->ejecutar();
        $talleresMap = [];
        foreach ($talleres as $taller) {
            $talleresMap[$taller->getId()] = $taller->getNombreTaller();
        }

        // Asegúrate de que $getAreaUseCase esté disponible en el scope del include
        include __DIR__ . '/../PDF/reporteObreros.php';
        $html = ob_get_clean();
        $stylesheet = file_get_contents(__DIR__ . '/../PDF/pdf.css');

        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html);
        $mpdf->Output("reporteObreros.pdf", "I"); // "I" para previsualizar
    }

    public function reporteInventarioPdf($params = null) {
        $mpdf = new Mpdf();

        ob_start();

        $inventarioRepo = new MysqlInventarioRepository();
        $getAllInventarioUseCase = new GetAllInventarioUseCase($inventarioRepo);

        $tallerRepo = new MysqlTallerRepository();
        $getTallerUseCase = new ObtenerTaller($tallerRepo);

        $tallerId = null;
        $tipo = null;

        if ($params !== null) {
            $paramArray = explode(',', $params);
            if (isset($paramArray[0]) && $paramArray[0] !== 'null') {
                $tallerId = (int)$paramArray[0];
            }
            if (isset($paramArray[1]) && $paramArray[1] !== 'null') {
                $tipo = urldecode($paramArray[1]);
            }
        }

        $inventario = $getAllInventarioUseCase->ejecutar();

        // Filtrar inventario
        $inventarioFiltrado = array_filter($inventario, function($item) use ($tallerId, $tipo) {
            $matchTaller = ($tallerId === null || $item->getIdTaller() == $tallerId);
            $matchTipo = ($tipo === null || $item->getTipo() == $tipo);
            return $matchTaller && $matchTipo;
        });

        $inventario = $inventarioFiltrado;

        $titulo = "Reporte de Almacén";
        if ($tallerId !== null) {
            $taller = $getTallerUseCase->ejecutar($tallerId);
            $titulo .= " - Taller: " . ($taller ? $taller->getNombreTaller() : 'Desconocido');
        }
        if ($tipo !== null) {
            $titulo .= " - Tipo: " . htmlspecialchars($tipo);
        }

        $talleres = (new \Application\UseCases\Taller\GetAllTalleresUseCase($tallerRepo))->ejecutar();
        $talleresMap = [];
        foreach ($talleres as $taller) {
            $talleresMap[$taller->getId()] = $taller->getNombreTaller();
        }

        include __DIR__ . '/../PDF/reporteInventario.php';
        $html = ob_get_clean();
        $stylesheet = file_get_contents(__DIR__ . '/../PDF/pdf.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html);
        $mpdf->Output("reporteInventario.pdf", "I");
    } // "I" para previsualizar
    
}
?>
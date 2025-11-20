<?php
namespace Infrastructure\Web\Controllers;

use Application\UseCases\ReporteFallas\GetReporteFallasUseCase;
use Application\UseCases\ReporteFallas\CargarSatisfaccionReporteFallasUseCase;
use Infrastructure\Persistence\Adapter\MysqlReporteFallasRepository;

class SatisfaccionController {

    public function formulario($token) {
        $data = [];

        if (empty($token)) {
            // Error: Usamos la nueva función de mensajes
            $this->mostrarMensajePublico("Enlace Inválido", "El enlace que ha seguido está incompleto.", 'danger', 400);
            return;
        }

        $reporteRepo = new MysqlReporteFallasRepository();
        
        $reporteId = $reporteRepo->validarTokenSatisfaccion($token);
        if ($reporteId === null) {
            // Error: Usamos la nueva función de mensajes
            $this->mostrarMensajePublico("Enlace Expirado", "El enlace de satisfacción ha expirado o no es válido.", 'danger', 403);
            return;
        }

        $getReporteUseCase = new GetReporteFallasUseCase($reporteRepo);
        $falla = $getReporteUseCase->ejecutar($reporteId);

        if (!$falla || $falla->getEstado() !== 'Finalizada' || $falla->getSatisfaccion() !== 0) {
            // Error: Usamos la nueva función de mensajes
            $this->mostrarMensajePublico("Encuesta no disponible", "Este reporte no está listo para recibir una encuesta o ya ha sido completada.", 'warning', 409);
            return;
        }

        $data['title'] = 'Encuesta de Satisfacción';
        $data['falla'] = $falla;
        $data['token'] = $token; 

        // Cargar la vista del formulario (esta sigue igual)
        include __DIR__ . '/../Views/reporteFallas/satisfaccionPublica.php';
    }

    public function guardarSatisfaccion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $umypf_n = $_POST['umypf_n'] ?? null;
            $token = $_POST['token'] ?? null; 
            $satisfaccion = $_POST['satisfaccion'] ?? '';
            $comentarios = $_POST['comentarios'] ?? '';

            $reporteFallasRepo = new MysqlReporteFallasRepository();

            $reporteIdFromToken = $reporteFallasRepo->validarTokenSatisfaccion($token);
            if ($reporteIdFromToken === null || (int)$reporteIdFromToken !== (int)$umypf_n) {
                // Error: Usamos la nueva función de mensajes
                $this->mostrarMensajePublico("Enlace no válido", "No se pudo registrar su respuesta. El enlace puede haber expirado o ser incorrecto.", 'danger', 403);
                return;
            }

            $getReporteFallaUseCase = new GetReporteFallasUseCase($reporteFallasRepo);
            $falla = $getReporteFallaUseCase->ejecutar((int)$umypf_n);

            if (!$falla || $falla->getEstado() !== 'Finalizada' || $falla->getSatisfaccion() !== 0) {
                // Error: Usamos la nueva función de mensajes
                $this->mostrarMensajePublico("No se puede procesar", "Este reporte no está listo para recibir una encuesta o ya ha sido completada.", 'warning', 409);
                return;
            }

            try {
                $cargarSatisfaccionUseCase = new CargarSatisfaccionReporteFallasUseCase($reporteFallasRepo);
                $cargarSatisfaccionUseCase->ejecutar((int)$umypf_n, $satisfaccion, $comentarios);

                // Éxito: Usamos la nueva función de mensajes
                $this->mostrarMensajePublico("¡Gracias por su respuesta!", "Su calificación ha sido registrada exitosamente.", 'success', 200);

            } catch (\Exception $e) {
                // Error: Usamos la nueva función de mensajes
                $this->mostrarMensajePublico("Error del Servidor", "Ocurrió un problema al registrar su respuesta. Por favor, intente más tarde.", 'danger', 500);
            }
        } else {
            // Acceso no permitido (no es POST)
            $this->mostrarMensajePublico("Acceso Denegado", "Método no permitido.", 'danger', 405);
        }
    }

    private function mostrarMensajePublico(string $titulo, string $mensaje, string $tipo = 'danger', int $httpCode = 200) {
        
        http_response_code($httpCode);

        // Definir icono y color basado en el tipo
        $icon = 'bi-exclamation-triangle-fill'; // Default (danger)
        $colorClass = 'text-danger';

        if ($tipo === 'success') {
            $icon = 'bi-check-circle-fill';
            $colorClass = 'text-success';
        } elseif ($tipo === 'warning') {
            $icon = 'bi-exclamation-triangle-fill';
            $colorClass = 'text-warning';
        }

        $base_url = base_url();

        // Usamos Heredoc para imprimir el HTML completo
        echo <<<HTML
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$titulo}</title>
    
    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CDN de Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
        }
        .container-fluid { 
            max-width: 700px; 
        }
        .card { 
            margin-top: 50px; 
            border: 0; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 50px;
        }
        .status-icon {
            font-size: 4.5rem; /* Icono grande */
            line-height: 1;
        }
    </style>
</head>
<body>

<center class="mt-5"><img src="{$base_url}/public/assets/images/cintillo.jpeg" class="cintillo-login"></center>
    
<div class="container-fluid content-inner py-0 pt-2">
<div class="row">
</div>
<div class="row mt-2">
  <div class="col-sm-12">
   <div class="card">
    <div class="card-body p-4 p-md-5 text-center"> <!-- Centramos el contenido -->

        <!-- Icono de estado dinámico -->
        <div class="mb-3">
            <i class="bi {$icon} {$colorClass} status-icon"></i>
        </div>

        <!-- Título dinámico -->
        <h2 class="card-title mb-3">{$titulo}</h2>
        
        <!-- Mensaje dinámico -->
        <p class="lead">{$mensaje}</p>

    </div>
   </div>
  </div>
 </div>
</div>
</body>
</html>
HTML;
        // Terminamos la ejecución para asegurar que no se envíe más contenido
        exit;
    }
}
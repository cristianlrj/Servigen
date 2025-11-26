<?php
namespace Infrastructure\Web\Controllers;

// Importamos los Casos de Uso que vamos a llamar
use Application\UseCases\ReporteFallas\RegistrarReporteFallasUseCase;
// Importamos el Caso de Uso que vamos a llamar
use Application\UseCases\ReporteFallas\GetAllReporteFallasUseCase; // Corregido: Ya estaba importado
use Application\UseCases\ReporteFallas\GetReporteFallasUseCase;
use Application\UseCases\Taller\ObtenerTaller;
use Application\UseCases\Taller\getAllTalleresUseCase;
use Application\UseCases\Usuario\getUsuarioUseCase;
use Application\UseCases\Departamento\GetAllDepartamentosUseCase;
use Application\UseCases\Departamento\GetDepartamentoUseCase;


use Application\UseCases\Departamento\ObtenerDepartamentosUseCase; // Importar el caso de uso correcto
use Application\UseCases\ReporteFallas\EditarReporteFallasUseCase;
use Application\UseCases\ReporteFallas\CargarSatisfaccionReporteFallasUseCase;
use Application\UseCases\ReporteFallas\GuardarTokenSatisfaccionUseCase;
use Application\UseCases\Inventario\GetAllInventarioUseCase;
use Infrastructure\Services\EmailService;
// Importamos las implementaciones de Repositorio que vamos a inyectar
use Infrastructure\Persistence\Adapter\MysqlReporteFallasRepository;
use Infrastructure\Persistence\Adapter\MysqlTallerRepository;
use Infrastructure\Persistence\Adapter\MysqlInventarioRepository;
use Infrastructure\Persistence\Adapter\MysqlUsuarioRepository;
use Infrastructure\Persistence\Adapter\MysqlDepartamentoRepository;

class ReporteFallasController extends BaseController
{

    public function listar()
    {

        $this->data['title'] = 'Reportes de Fallas';

        try {
            $reporteFallasRepo = new MysqlReporteFallasRepository();
            $tallerRepo = new MysqlTallerRepository();
            $usuarioRepo = new MysqlUsuarioRepository();

            $getAllReporteFallas = new GetAllReporteFallasUseCase(
                $reporteFallasRepo
            );

            $getUsuarioUseCase = new getUsuarioUseCase($usuarioRepo);
            $getTallerUseCase = new ObtenerTaller($tallerRepo);

            $resultados = $getAllReporteFallas->ejecutar();

            $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);
            $talleres = $getAllTalleresUseCase->ejecutar();

            $departamentoRepo = new MysqlDepartamentoRepository();
            $getDepartamentoUseCase = new GetDepartamentoUseCase($departamentoRepo); // Usar el caso de uso correcto


            $this->data['talleres'] = $talleres;
            $this->data['fallas'] = $resultados;

        } catch (\Exception $e) {
            // Manejar error si el caso de uso falla
            $this->data['fallas'] = [];
            $this->data['talleres'] = [];
            $this->data['usuarios'] = [];
            $_SESSION['error'] = "Error al cargar los datos: " . $e->getMessage();
        }

        $data = $this->data;
        include __DIR__ . '/../Views/reporteFallas/listar.php';
    }

    public function crear()
    {
        $this->data['title'] = 'Crear Reporte de Fallas';

        // El método 'crear' también necesita un caso de uso para obtener los talleres
        // Ej: GetDatosParaFormularioFallaUseCase
        $tallerRepo = new MysqlTallerRepository();
        $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);
        $this->data['talleres'] = $getAllTalleresUseCase->ejecutar();

        // Obtener departamentos
        $departamentoRepo = new MysqlDepartamentoRepository();
        $getAllDepartamentosUseCase = new ObtenerDepartamentosUseCase($departamentoRepo); // Usar el caso de uso correcto
        $this->data['departamentos'] = $getAllDepartamentosUseCase->ejecutar();

        $data = $this->data;
        include __DIR__ . '/../Views/reporteFallas/crear.php';
    }

    public function editar($id)
    {
        $this->data['title'] = 'Actualizar Reporte de Fallas';

        $reporteFallasRepo = new MysqlReporteFallasRepository();
        $getReporteFallaUseCase = new GetReporteFallasUseCase($reporteFallasRepo);

        $falla = $getReporteFallaUseCase->ejecutar($id);

        $idTallerFalla = $falla->getIdTaller();

        // --- INICIO DE LA MODIFICACIÓN ---
        // 1. Obtener todos los artículos del inventario
        $inventarioRepo = new MysqlInventarioRepository();
        $getAllInventarioUseCase = new GetAllInventarioUseCase($inventarioRepo);
        $inventarioCompleto = $getAllInventarioUseCase->ejecutar();

        // 2. Filtrar por tipo ('Materia prima', 'Consumible') Y por el taller de la falla
        $materialesFiltrados = array_filter($inventarioCompleto, function ($item) use ($idTallerFalla) {
            $esTipoCorrecto = in_array($item->getTipo(), ['Materia prima', 'Consumible']);
            $esDelTallerCorrecto = $item->getIdTaller() == $idTallerFalla;
            return $esTipoCorrecto && $esDelTallerCorrecto;
        });

        $this->data['materiales'] = $materialesFiltrados;
        $this->data['falla'] = $falla;

        // --- FIN DE LA MODIFICACIÓN ---


        $data = $this->data;
        include __DIR__ . '/../Views/reporteFallas/actualizar.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recoger datos del formulario
            $idUsuario = $_SESSION['usuario_id']; // Asumiendo que el ID del usuario está en la sesión
            $idTaller = $_POST['id_taller'] ?? null;
            $unidadSolicitante = $_POST['unidad_solicitante'] ?? '';
            $personaContacto = $_POST['persona_contacto'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $emailContacto = $_POST['email_contacto'] ?? null;

            // Validar datos (ej. campos obligatorios)
            if (empty($idTaller) || empty($unidadSolicitante) || empty($personaContacto) || empty($descripcion) || empty($emailContacto)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header('Location: ' . base_url() . '/reporteFallas/crear');
                return;
            }


            try {
                $reporteFallasRepo = new MysqlReporteFallasRepository();
                $registrarReporteFallasUseCase = new RegistrarReporteFallasUseCase($reporteFallasRepo);

                $registrarReporteFallasUseCase->ejecutar(
                    $idUsuario,
                    $idTaller,
                    $unidadSolicitante,
                    $personaContacto,
                    $emailContacto,
                    $descripcion
                );

                $_SESSION['success'] = "Reporte de falla creado exitosamente.";
                header('Location: ' . base_url() . '/reporteFallas/listar');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al crear el reporte de falla: " . $e->getMessage();
                header('Location: ' . base_url() . '/reporteFallas/crear');
            }
        } else {
            header('Location: ' . base_url() . '/reporteFallas/crear');
        }
    }

    public function enviarCorreoSatisfaccion($umypf_n)
    {
        if (empty($umypf_n)) {
            $_SESSION['error'] = "ID de reporte no proporcionado.";
            header('Location: ' . base_url() . '/reporteFallas/listar');
            return;
        }

        try {
            $reporteFallasRepo = new MysqlReporteFallasRepository();
            $getReporteUseCase = new GetReporteFallasUseCase($reporteFallasRepo);
            $falla = $getReporteUseCase->ejecutar((int) $umypf_n);

            if (!$falla || !$falla->getEmailContacto()) {
                $_SESSION['error'] = "El reporte no existe o no tiene un correo de contacto asociado.";
                header('Location: ' . base_url() . '/reporteFallas/listar');
                return;
            }

            $emailCliente = $falla->getEmailContacto();

            // Usar el nuevo caso de uso para generar y guardar el token
            $guardarTokenUseCase = new GuardarTokenSatisfaccionUseCase($reporteFallasRepo);
            $token = $guardarTokenUseCase->ejecutar((int) $umypf_n);

            // El enlace ahora apunta al nuevo controlador público
            $link = base_url() . "/satisfaccion/formulario/" . $token;

            $emailService = new EmailService();
            $body = "<h1>Encuesta de Satisfacción</h1><p>Por favor, ayúdenos a mejorar nuestro servicio calificando la atención recibida para el reporte de falla.</p><p>Haga clic en el siguiente enlace para acceder a la encuesta:</p><p><a href='{$link}' style='background-color: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Calificar Servicio</a></p><p>Este enlace será válido por 7 días.</p>";
            $emailService->send($emailCliente, "Cliente", "Encuesta de Satisfacción - Reporte #" . $umypf_n, $body);

            $_SESSION['success'] = "Correo de satisfacción enviado exitosamente.";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error al enviar el correo de satisfacción: " . $e->getMessage();
        }
        header('Location: ' . base_url() . '/reporteFallas/listar');
    }

    public function actualizarEstado()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $umypf_n = $_POST['umypf_n'] ?? null;
            $descripcion = $_POST['descripcion'] ?? '';
            $estado = $_POST['estado'] ?? '';
            $materiales = $_POST['materiales'] ?? []; // Obtener materiales del formulario

            if (empty($umypf_n) || empty($descripcion) || empty($estado)) {
                $_SESSION['error'] = "Todos los campos son obligatorios para actualizar el estado.";
                header('Location: ' . base_url() . '/reporteFallas/editar/' . $umypf_n);
                return;
            }

            try {
                $reporteFallasRepo = new MysqlReporteFallasRepository();
                $editarReporteFallasUseCase = new EditarReporteFallasUseCase($reporteFallasRepo);

                $editarReporteFallasUseCase->ejecutar((int) $umypf_n, $descripcion, $estado);

                // --- Lógica para Movimientos de Inventario ---
                if ($estado === 'Finalizada' && !empty($materiales)) {
                    $inventarioRepo = new MysqlInventarioRepository();
                    $registrarMovimientoUseCase = new \Application\UseCases\Inventario\RegistrarMovimientoInventarioUseCase($inventarioRepo);

                    foreach ($materiales as $material) {
                        $idMaterial = $material['id'] ?? null;
                        $cantidad = $material['cantidad'] ?? 0;

                        if ($idMaterial && $cantidad > 0) {
                            $registrarMovimientoUseCase->ejecutar(
                                (int) $idMaterial,
                                (int) $cantidad,
                                'salida',
                                "Reporte de Falla #$umypf_n"
                            );
                        }
                    }
                }
                // ---------------------------------------------

                $_SESSION['success'] = "Estado del reporte de falla actualizado exitosamente.";
                header('Location: ' . base_url() . '/reporteFallas/listar');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al actualizar el estado del reporte: " . $e->getMessage();
                header('Location: ' . base_url() . '/reporteFallas/editar/' . $umypf_n);
            }
        } else {
            header('Location: ' . base_url() . '/reporteFallas/listar');
        }
    }


}

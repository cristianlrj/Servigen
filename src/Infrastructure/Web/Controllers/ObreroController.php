<?php
namespace Infrastructure\Web\Controllers;

use Application\UseCases\Obrero\ConsultObreroByCedula;
use Application\UseCases\Obrero\RegisterObrero;
use Application\UseCases\Obrero\EditarObreroUseCase;
use Application\UseCases\Obrero\AssignArea;
use Application\UseCases\Obrero\GetAllObreros;
use Application\UseCases\Obrero\GetObrero;
use Application\UseCases\Area\GetAllAreasUseCase;
use Infrastructure\Persistence\Adapter\MysqlAreaRepository;
use Application\UseCases\Taller\getAllTalleresUseCase;
use Application\UseCases\Taller\GetTallerUseCase;
use Application\UseCases\Taller\ObtenerTaller;
use Infrastructure\Persistence\Adapter\MysqlObreroRepository;
use Infrastructure\Persistence\Adapter\MysqlTallerRepository;
use Infrastructure\Persistence\External\ObreroApiService;

class ObreroController extends BaseController {

    public function listar() {
        $tallerRepo = new MysqlTallerRepository();
        $areaRepo = new MysqlAreaRepository();
        $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);
        $getTallerUseCase = new ObtenerTaller($tallerRepo);


        $obreroRepo = new MysqlObreroRepository();
        $getAllObrerosUseCase = new GetAllObreros($obreroRepo);
        $obreros = $getAllObrerosUseCase->ejecutar();
        $talleres = $getAllTalleresUseCase->ejecutar();
        $getAllAreasUseCase = new GetAllAreasUseCase($areaRepo);

        $this->data['areas'] = $getAllAreasUseCase->ejecutar();

        $this->data['obreros'] = $obreros;
        
        // Crear un mapa de talleres por ID para un acceso rápido
        $talleresMap = [];
        foreach ($talleres as $taller) {
            $talleresMap[$taller->getId()] = $taller->getNombreTaller();
        }

        $this->data['talleresMap'] = $talleresMap;

        $this->data['talleres'] = $talleres;
        $this->data['title'] = "SERVIGEN - Listar Obreros";

        $data = $this->data;
        include __DIR__ . '/../Views/obrero/listar.php';
    }

     public function crear() {

        $this->data['title'] = "SERVIGEN - Registrar Obrero";
        $tallerRepo = new MysqlTallerRepository();
        $areaRepo = new MysqlAreaRepository();

        $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);
        $getAllAreasUseCase = new GetAllAreasUseCase($areaRepo);

        $this->data['talleres'] = $getAllTalleresUseCase->ejecutar();
        $this->data['areas'] = $getAllAreasUseCase->ejecutar();

        $data = $this->data;
        include __DIR__ . '/../Views/obrero/crear.php';
    }

      public function editar($id) {
        $this->data['title'] = 'SERVIGEN - Editar Obrero';
        
        $obreroRepo = new MysqlObreroRepository();
        $GetObrero = new GetObrero($obreroRepo); 

        $tallerRepo = new MysqlTallerRepository();
        $areaRepo = new MysqlAreaRepository();

        $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);
        $getAllAreasUseCase = new GetAllAreasUseCase($areaRepo);

        $obrero = $GetObrero->ejecutar($id);

        $this->data['obrero'] = $obrero;
        $this->data['talleres'] = $getAllTalleresUseCase->ejecutar();
        $this->data['areas'] = $getAllAreasUseCase->ejecutar();
        
        $data = $this->data;
        include __DIR__ . '/../Views/obrero/editar.php';
    }

    public function actualizarArea() {
        //print_r($_POST);
        header('Content-Type: application/json');
        $response = ['status' => 'error', 'message' => 'Solicitud inválida.'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            

            if (isset($_POST['id']) && isset($_POST['area'])) {
                $obreroId = (int)$_POST['id'];
                $newArea = $_POST['area'];

                $obreroRepo = new MysqlObreroRepository();
                $assignAreaUseCase = new AssignArea($obreroRepo);

                try {
                    $assignAreaUseCase->ejecutar($obreroId, $newArea);
                    $mensaje = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    Ubicación de área actualizada correctamente.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    $response = ['status' => 'success', 'message' => $mensaje];
                } catch (\Exception $e) {
                    $mensaje = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    '.$e->getMessage().'
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    $response = ['status' => 'error', 'message' => $mensaje];
                }
            } else {
                $mensaje = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Datos incompletos para la actualización.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                $response['message'] = $mensaje;
            }
        }
        echo json_encode($response);
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cedula = $_POST['cedula'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $cargo = $_POST['cargo'] ?? '';
            $habilidades = $_POST['habilidades'] ?? null;
            $tallerId = !empty($_POST['taller']) ? (int)$_POST['taller'] : null;
            $area = $_POST['area'] ?? null;

            // Validación 1: Que el cargo sea 'OBRERO'
            if (strtoupper($cargo) !== 'OBRERO') {
                $_SESSION['error'] = "El registro ha fallado: El cargo de la persona no es 'Obrero'.";
                header('Location: ' . base_url() . '/obrero/crear');
                return;
            }

            // Validación 2: Campos básicos no vacíos
            if (empty($cedula) || empty($nombre) || empty($apellido) || empty($cargo) || $tallerId === null) {
                $_SESSION['error'] = "Todos los campos obligatorios deben ser completados (Cédula, Nombre, Apellido, Cargo, Taller).";
                header('Location: ' . base_url() . '/obrero/crear');
                return;
            }

            try {
                $obreroRepo = new MysqlObreroRepository();
                $registrarObreroUseCase = new RegisterObrero($obreroRepo);
                $registrarObreroUseCase->ejecutar($cedula, $nombre, $apellido, $cargo, $habilidades, $tallerId, $area);

                $_SESSION['success'] = "Obrero registrado exitosamente.";
                header('Location: ' . base_url() . '/obrero/listar');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al registrar el obrero: " . $e->getMessage();
                header('Location: ' . base_url() . '/obrero/crear');
            }
        } else {
            header('Location: ' . base_url() . '/obrero/crear');
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $cedula = $_POST['cedula'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $cargo = $_POST['cargo'] ?? '';
            $habilidades = $_POST['habilidades'] ?? null;
            $tallerId = !empty($_POST['taller']) ? (int)$_POST['taller'] : null;
            $area = $_POST['area'] ?? null;

            // Validación
            if (empty($id) || empty($cedula) || empty($nombre) || empty($apellido) || empty($cargo) || $tallerId === null) {
                $_SESSION['error'] = "Faltan datos para actualizar el obrero.";
                header('Location: ' . base_url() . '/obrero/editar/' . $id);
                return;
            }

            try {
                $obreroRepo = new MysqlObreroRepository();
                $editarObreroUseCase = new EditarObreroUseCase($obreroRepo);
                $editarObreroUseCase->ejecutar((int)$id, $cedula, $nombre, $apellido, $cargo, $habilidades, $tallerId, $area);

                $_SESSION['success'] = "Obrero actualizado exitosamente.";
                header('Location: ' . base_url() . '/obrero/listar');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al actualizar el obrero: " . $e->getMessage();
                header('Location: ' . base_url() . '/obrero/editar/' . $id);
            }
        }
    }

    public function buscarPorApi() {
        header('Content-Type: application/json');
        $response = ['status' => 'error', 'message' => 'Solicitud inválida.'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cedula = $_POST['cedula'] ?? null;
            $token = API_TOKEN; // El token se obtiene de la configuración global

            if ($cedula && $token) {
                $obreroApiService = new ObreroApiService();
                $consultObreroByCedulaUseCase = new ConsultObreroByCedula($obreroApiService);

                try {
                    $obrero = $consultObreroByCedulaUseCase->ejecutar($cedula, $token);

                    if ($obrero) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Obrero encontrado.',
                            'obrero' => [
                                'id' => $obrero->getId(),
                                'nombre' => $obrero->getNombre(),
                                'cedula' => $obrero->getCedula(),
                                'cargo' => $obrero->getCargo()
                            ]
                        ];
                    } else {
                        $response = ['status' => 'error', 'message' => 'Obrero no encontrado en la API externa.'];
                    }
                } catch (\Exception $e) {
                    $response = ['status' => 'error', 'message' => 'Error al consultar la API: ' . $e->getMessage()];
                }
            } else {
                $response['message'] = 'Cédula o token no proporcionados.';
            }
        }
        echo json_encode($response);
    }
}
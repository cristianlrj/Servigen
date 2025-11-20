<?php
namespace Infrastructure\Web\Controllers;

use Application\UseCases\Area\CrearAreaUseCase;
use Application\UseCases\Area\GetAllAreasUseCase;
use Application\UseCases\Area\ActualizarAreaUseCase;
use Application\UseCases\Area\EliminarAreaUseCase;
use Infrastructure\Persistence\Adapter\MysqlAreaRepository;

class AreaController extends BaseController {

    public function listar() {
        $this->data['title'] = 'Gestión de Áreas';
        $areaRepo = new MysqlAreaRepository();
        $getAllUseCase = new GetAllAreasUseCase($areaRepo);
        $this->data['areas'] = $getAllUseCase->ejecutar();

        $data = $this->data;
        include __DIR__ . '/../Views/area/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';

            if (empty($nombre)) {
                $_SESSION['error'] = "El nombre del área es obligatorio.";
                header('Location: ' . base_url() . '/area/listar');
                return;
            }

            try {
                $areaRepo = new MysqlAreaRepository();
                $crearUseCase = new CrearAreaUseCase($areaRepo);
                $crearUseCase->ejecutar($nombre);
                $_SESSION['success'] = "Área registrada exitosamente.";
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al registrar el área: " . $e->getMessage();
            }
        }
        header('Location: ' . base_url() . '/area/listar');
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $nombre = $_POST['nombre'] ?? '';

            if (empty($id) || empty($nombre)) {
                $_SESSION['error'] = "Faltan datos para actualizar el área.";
                header('Location: ' . base_url() . '/area/listar');
                return;
            }

            try {
                $areaRepo = new MysqlAreaRepository();
                $actualizarUseCase = new ActualizarAreaUseCase($areaRepo);
                $actualizarUseCase->ejecutar((int)$id, $nombre);
                $_SESSION['success'] = "Área actualizada exitosamente.";
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al actualizar el área: " . $e->getMessage();
            }
        }
        header('Location: ' . base_url() . '/area/listar');
    }

    public function eliminar($id) {
        if (empty($id)) {
            $_SESSION['error'] = "No se proporcionó un ID para eliminar.";
            header('Location: ' . base_url() . '/area/listar');
            return;
        }

        try {
            $areaRepo = new MysqlAreaRepository();
            $eliminarUseCase = new EliminarAreaUseCase($areaRepo);
            $eliminarUseCase->ejecutar((int)$id);
            $_SESSION['success'] = "Área desactivada exitosamente.";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error al desactivar el área: " . $e->getMessage();
        }
        header('Location: ' . base_url() . '/area/listar');
    }
}
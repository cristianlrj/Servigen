<?php
namespace Infrastructure\Web\Controllers;

use Application\UseCases\Taller\CrearTallerUseCase;
use Application\UseCases\Taller\GetAllTalleresUseCase;
use Application\UseCases\Taller\ActualizarTallerUseCase;
use Application\UseCases\Taller\EliminarTallerUseCase;
use Infrastructure\Persistence\Adapter\MysqlTallerRepository;

class TallerController extends BaseController {

    public function listar() {
        $this->data['title'] = 'Gestión de Talleres';
        $tallerRepo = new MysqlTallerRepository();
        $getAllUseCase = new GetAllTalleresUseCase($tallerRepo);
        $this->data['talleres'] = $getAllUseCase->ejecutar();

        $data = $this->data;
        include __DIR__ . '/../Views/taller/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';

            if (empty($nombre)) {
                $_SESSION['error'] = "El nombre del taller es obligatorio.";
                header('Location: ' . base_url() . '/taller/listar');
                return;
            }

            try {
                $tallerRepo = new MysqlTallerRepository();
                $crearUseCase = new CrearTallerUseCase($tallerRepo);
                $crearUseCase->ejecutar($nombre);
                $_SESSION['success'] = "Taller registrado exitosamente.";
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al registrar el taller: " . $e->getMessage();
            }
        }
        header('Location: ' . base_url() . '/taller/listar');
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $nombre = $_POST['nombre'] ?? '';

            if (empty($id) || empty($nombre)) {
                $_SESSION['error'] = "Faltan datos para actualizar el taller.";
                header('Location: ' . base_url() . '/taller/listar');
                return;
            }

            try {
                $tallerRepo = new MysqlTallerRepository();
                $actualizarUseCase = new ActualizarTallerUseCase($tallerRepo);
                $actualizarUseCase->ejecutar((int)$id, $nombre);
                $_SESSION['success'] = "Taller actualizado exitosamente.";
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al actualizar el taller: " . $e->getMessage();
            }
        }
        header('Location: ' . base_url() . '/taller/listar');
    }

    public function eliminar($id) {
        if (empty($id)) {
            $_SESSION['error'] = "No se proporcionó un ID para eliminar.";
            header('Location: ' . base_url() . '/taller/listar');
            return;
        }

        try {
            $tallerRepo = new MysqlTallerRepository();
            $eliminarUseCase = new EliminarTallerUseCase($tallerRepo);
            $eliminarUseCase->ejecutar((int)$id);
            $_SESSION['success'] = "Taller desactivado exitosamente.";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error al desactivar el taller: " . $e->getMessage();
        }
        header('Location: ' . base_url() . '/taller/listar');
    }
}
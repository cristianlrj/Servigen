<?php
namespace Infrastructure\Web\Controllers;

use Application\UseCases\MantenimientoPreventivo\CrearMantenimientoPreventivoUseCase;
use Application\UseCases\MantenimientoPreventivo\GetAllMantenimientosPreventivosUseCase;
use Application\UseCases\Taller\getAllTalleresUseCase;
use Application\UseCases\Usuario\getUsuarioUseCase;
use Infrastructure\Persistence\Adapter\MysqlMantenimientoPreventivoRepository;
use Infrastructure\Persistence\Adapter\MysqlTallerRepository;
use Infrastructure\Persistence\Adapter\MysqlUsuarioRepository;

class MantenimientoPreventivoController extends BaseController {

    public function listar() {
        $this->data['title'] = 'Mantenimientos Preventivos';

        $mantenimientoRepo = new MysqlMantenimientoPreventivoRepository();
        $getAllUseCase = new GetAllMantenimientosPreventivosUseCase($mantenimientoRepo);
        $this->data['mantenimientos'] = $getAllUseCase->ejecutar();

        // Para mostrar nombres en la tabla
        $this->data['getTallerUseCase'] = new \Application\UseCases\Taller\ObtenerTaller(new MysqlTallerRepository());
        $this->data['getUsuarioUseCase'] = new getUsuarioUseCase(new MysqlUsuarioRepository());

        $data = $this->data;
        include __DIR__ . '/../Views/mantenimientoPreventivo/listar.php';
    }

    public function crear() {
        $this->data['title'] = 'Programar Mantenimiento Preventivo';

        $tallerRepo = new MysqlTallerRepository();
        $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);
        $this->data['talleres'] = $getAllTalleresUseCase->ejecutar();

        $data = $this->data;
        include __DIR__ . '/../Views/mantenimientoPreventivo/crear.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_equipo = $_POST['nombre_equipo'] ?? '';
            $id_taller = $_POST['id_taller'] ?? null;
            $tipo_mantenimiento = $_POST['tipo_mantenimiento'] ?? '';
            $descripcion_tarea = $_POST['descripcion_tarea'] ?? '';
            $fecha_programada = $_POST['fecha_programada'] ?? '';
            $id_usuario = $_SESSION['usuario_id'];

            if (empty($nombre_equipo) || empty($id_taller) || empty($tipo_mantenimiento) || empty($descripcion_tarea) || empty($fecha_programada)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header('Location: ' . base_url() . '/mantenimientoPreventivo/crear');
                return;
            }

            try {
                $repo = new MysqlMantenimientoPreventivoRepository();
                $useCase = new CrearMantenimientoPreventivoUseCase($repo);
                $useCase->ejecutar(
                    $nombre_equipo,
                    (int)$id_taller,
                    (int)$id_usuario,
                    $tipo_mantenimiento,
                    $descripcion_tarea,
                    $fecha_programada
                );

                $_SESSION['success'] = "Mantenimiento preventivo programado exitosamente.";
                header('Location: ' . base_url() . '/mantenimientoPreventivo/listar');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al programar el mantenimiento: " . $e->getMessage();
                header('Location: ' . base_url() . '/mantenimientoPreventivo/crear');
            }
        } else {
            header('Location: ' . base_url() . '/mantenimientoPreventivo/crear');
        }
    }
}
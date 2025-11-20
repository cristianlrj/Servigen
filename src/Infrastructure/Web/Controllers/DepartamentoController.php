<?php
namespace Infrastructure\Web\Controllers;

use Application\UseCases\Departamento\ObtenerDepartamentosUseCase;
use Application\UseCases\Departamento\RegistrarDepartamentoUseCase;
use Application\UseCases\Departamento\GetDepartamentoUseCase;
use Application\UseCases\Departamento\EditarDepartamentoUseCase;
use Application\UseCases\Departamento\EliminarDepartamentoUseCase;
use Infrastructure\Persistence\Adapter\MysqlDepartamentoRepository;

class DepartamentoController extends BaseController {

    public function listar() {
        $this->data['title'] = 'Departamentos';
        try {
            $repo = new MysqlDepartamentoRepository();
            $useCase = new ObtenerDepartamentosUseCase($repo);
            $this->data['departamentos'] = $useCase->ejecutar();
        } catch (\Exception $e) {
            $this->data['departamentos'] = [];
            $_SESSION['error'] = "Error al cargar los departamentos: " . $e->getMessage();
        }
        $data = $this->data;
        include __DIR__ . '/../Views/departamento/listar.php';
    }

    public function crear() {
        $this->data['title'] = 'Crear Departamento';
        $data = $this->data;
        include __DIR__ . '/../Views/departamento/crear.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';

            if (empty($nombre)) {
                $_SESSION['error'] = "El nombre del departamento es obligatorio.";
                header('Location: ' . base_url() . '/departamento/crear');
                return;
            }

            try {
                $repo = new MysqlDepartamentoRepository();
                $useCase = new RegistrarDepartamentoUseCase($repo);
                $useCase->ejecutar($nombre);

                $_SESSION['success'] = "Departamento creado exitosamente.";
                header('Location: ' . base_url() . '/departamento/listar');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al crear el departamento: " . $e->getMessage();
                header('Location: ' . base_url() . '/departamento/crear');
            }
        }
    }

    public function editar($id) {
        try {
            $repo = new MysqlDepartamentoRepository();
            $useCase = new GetDepartamentoUseCase($repo);
            $departamento = $useCase->ejecutar((int)$id);

            if (!$departamento) {
                $_SESSION['error'] = "Departamento no encontrado.";
                header('Location: ' . base_url() . '/departamento/listar');
                return;
            }

            $this->data['title'] = 'Editar Departamento';
            $this->data['departamento'] = $departamento;
            $data = $this->data;
            include __DIR__ . '/../Views/departamento/editar.php';

        } catch (\Exception $e) {
            $_SESSION['error'] = "Error al cargar el departamento: " . $e->getMessage();
            header('Location: ' . base_url() . '/departamento/listar');
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $nombre = $_POST['nombre'] ?? '';

            if (empty($id) || empty($nombre)) {
                $_SESSION['error'] = "Faltan datos para actualizar.";
                header('Location: ' . base_url() . '/departamento/listar');
                return;
            }

            try {
                $repo = new MysqlDepartamentoRepository();
                $useCase = new EditarDepartamentoUseCase($repo);
                $useCase->ejecutar((int)$id, $nombre);

                $_SESSION['success'] = "Departamento actualizado exitosamente.";
                header('Location: ' . base_url() . '/departamento/listar');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al actualizar el departamento: " . $e->getMessage();
                header('Location: ' . base_url() . '/departamento/editar/' . $id);
            }
        }
    }

    public function eliminar($id) {
        $repo = new MysqlDepartamentoRepository();
        $useCase = new EliminarDepartamentoUseCase($repo);
        $useCase->ejecutar((int)$id); // El caso de uso llama al método 'delete' del repo, que ahora desactiva.
        $_SESSION['success'] = "Departamento desactivado correctamente.";
        header('Location: ' . base_url() . '/departamento/listar');
    }
}
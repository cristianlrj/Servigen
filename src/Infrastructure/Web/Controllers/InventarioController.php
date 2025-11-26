<?php
namespace Infrastructure\Web\Controllers;

// Importamos los Casos de Uso que vamos a llamar
use Application\UseCases\Inventario\RegistrarInventarioUseCase;
use Application\UseCases\Inventario\GetAllInventarioUseCase;
use Application\UseCases\Inventario\GetInventarioUseCase;
use Application\UseCases\Inventario\EditarInventarioUseCase;
use Application\UseCases\Inventario\EliminarInventarioUseCase;
use Application\UseCases\Inventario\RegistrarMovimientoInventarioUseCase;
use Application\UseCases\Inventario\GetMovimientosInventarioUseCase;
use Application\UseCases\Taller\getAllTalleresUseCase;

// Importamos las implementaciones de Repositorio que vamos a inyectar
use Infrastructure\Persistence\Adapter\MysqlInventarioRepository;
use Infrastructure\Persistence\Adapter\MysqlTallerRepository;

class InventarioController extends BaseController
{

    public function crear()
    {
        $this->data['title'] = "Registrar Item";

        $tallerRepo = new MysqlTallerRepository();
        $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);
        $this->data['talleres'] = $getAllTalleresUseCase->ejecutar();

        $data = $this->data;
        $talleres = $this->data['talleres'];
        include __DIR__ . '/../Views/inventario/crear.php';
    }

    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = $_POST['codigo'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $marca = $_POST['marca'] ?? null;
            $tipo = $_POST['tipo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $cantidad = $_POST['cantidad'] ?? null;
            $idTaller = $_POST['id_taller'] ?? null;

            if (empty($codigo) || empty($nombre) || empty($tipo) || empty($descripcion) || $cantidad === null || empty($idTaller)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: " . base_url() . "/inventario/crear");
                exit;
            }

            $inventarioRepo = new MysqlInventarioRepository();
            $registrarInventarioUseCase = new RegistrarInventarioUseCase($inventarioRepo);

            try {
                $registrarInventarioUseCase->ejecutar(
                    $codigo,
                    $nombre,
                    $marca,
                    $tipo,
                    $descripcion,
                    (int) $cantidad,
                    (int) $idTaller
                );
                $_SESSION['success'] = "Artículo de almacén registrado exitosamente.";
                header("Location: " . base_url() . "/inventario/listar");
                exit;
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al registrar el artículo de almacén: " . $e->getMessage();
                header("Location: " . base_url() . "/inventario/crear");
                exit;
            }
        }
    }

    public function listar()
    {
        $this->data['title'] = 'Listar Almacén';

        try {
            $inventarioRepo = new MysqlInventarioRepository();
            $tallerRepo = new MysqlTallerRepository();

            $getAllInventarioUseCase = new GetAllInventarioUseCase($inventarioRepo);
            $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);

            $inventario = $getAllInventarioUseCase->ejecutar();
            $talleres = $getAllTalleresUseCase->ejecutar();

            $this->data['inventario'] = $inventario;
            $this->data['talleres'] = $talleres;

        } catch (\Exception $e) {
            $this->data['inventario'] = [];
            $this->data['talleres'] = [];
            $_SESSION['error'] = "Error al cargar los datos del almacén: " . $e->getMessage();
        }

        $data = $this->data;

        // Obtener los tipos únicos de inventario para el filtro
        $tipos = [];
        if (!empty($this->data['inventario'])) {
            $tipos = array_unique(array_map(function ($item) {
                return $item->getTipo();
            }, $this->data['inventario']));
            sort($tipos);
        }
        $this->data['tipos'] = $tipos;

        include __DIR__ . '/../Views/inventario/listar.php';
    }

    public function editar($id)
    {
        $this->data['title'] = 'Editar Artículo de almacén';

        $inventarioRepo = new MysqlInventarioRepository();
        $getInventarioUseCase = new GetInventarioUseCase($inventarioRepo);

        $tallerRepo = new MysqlTallerRepository();
        $getAllTalleresUseCase = new getAllTalleresUseCase($tallerRepo);
        $this->data['talleres'] = $getAllTalleresUseCase->ejecutar();

        $articulo = $getInventarioUseCase->ejecutar($id);
        $this->data['articulo'] = $articulo;

        $data = $this->data;
        include __DIR__ . '/../Views/inventario/editar.php';
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $codigo = $_POST['codigo'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $marca = $_POST['marca'] ?? null;
            $tipo = $_POST['tipo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $idTaller = $_POST['id_taller'] ?? null;

            if (empty($id) || empty($codigo) || empty($nombre) || empty($tipo) || empty($descripcion) || empty($idTaller)) {
                $_SESSION['error'] = "Faltan datos para actualizar.";
                header('Location: ' . base_url() . '/inventario/editar/' . $id);
                return;
            }

            try {
                $repo = new MysqlInventarioRepository(); // Asumo que tienes este repositorio
                $useCase = new EditarInventarioUseCase($repo);
                $useCase->ejecutar(
                    (int) $id,
                    $codigo,
                    $nombre,
                    $marca,
                    $tipo,
                    $descripcion,
                    (int) $idTaller
                );

                $_SESSION['success'] = "Artículo actualizado exitosamente.";
                header('Location: ' . base_url() . '/inventario/listar');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al actualizar el artículo: " . $e->getMessage();
                header('Location: ' . base_url() . '/inventario/editar/' . $id);
            }
        }
    }

    public function eliminar($id)
    {
        try {
            $repo = new MysqlInventarioRepository();
            $useCase = new EliminarInventarioUseCase($repo);
            $useCase->ejecutar((int) $id);
            $_SESSION['success'] = "Artículo desactivado correctamente.";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error al desactivar el artículo: " . $e->getMessage();
        }
        header('Location: ' . base_url() . '/inventario/listar');
    }

    public function movimiento($id)
    {
        $this->data['title'] = 'Movimiento de almacén';

        $inventarioRepo = new MysqlInventarioRepository();
        $getInventarioUseCase = new GetInventarioUseCase($inventarioRepo);
        $getMovimientosUseCase = new GetMovimientosInventarioUseCase($inventarioRepo);

        $articulo = $getInventarioUseCase->ejecutar($id);
        if (!$articulo) {
            $_SESSION['error'] = "Artículo no encontrado.";
            header('Location: ' . base_url() . '/inventario/listar');
            exit;
        }

        $this->data['articulo'] = $articulo;
        $this->data['movimientos'] = $getMovimientosUseCase->ejecutar($id);

        $data = $this->data;
        include __DIR__ . '/../Views/inventario/movimiento.php';
    }

    public function registrarMovimiento()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_inventario = $_POST['id_inventario'] ?? null;
            $cantidad = $_POST['cantidad'] ?? null;
            $tipo_movimiento = $_POST['tipo_movimiento'] ?? '';
            $motivo = $_POST['motivo'] ?? null;

            if (empty($id_inventario) || empty($cantidad) || empty($tipo_movimiento)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header('Location: ' . base_url() . '/inventario/movimiento/' . $id_inventario);
                exit;
            }

            try {
                $repo = new MysqlInventarioRepository();
                $useCase = new RegistrarMovimientoInventarioUseCase($repo);
                $useCase->ejecutar((int) $id_inventario, (int) $cantidad, $tipo_movimiento, $motivo);

                $_SESSION['success'] = "Movimiento de almacén registrado exitosamente.";
                header('Location: ' . base_url() . '/inventario/listar/' . $id_inventario);
            } catch (\Exception $e) {
                $_SESSION['error'] = "Error al registrar el almacén: " . $e->getMessage();
                header('Location: ' . base_url() . '/inventario/listar/' . $id_inventario);
            }
        }
    }
}
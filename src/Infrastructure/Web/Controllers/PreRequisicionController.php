<?php
namespace Infrastructure\Web\Controllers;

use Application\UseCases\PreRequisicion\CrearPreRequisicionUseCase;
use Application\UseCases\ReporteFallas\GetReporteFallasUseCase;
use Application\UseCases\Inventario\GetAllInventarioUseCase;
use Application\UseCases\PreRequisicion\GetAllPreRequisicionesUseCase;
use Application\UseCases\Taller\getAllTalleresUseCase;
use Application\UseCases\Taller\ObtenerTaller;
use Infrastructure\Persistence\Adapter\MysqlPreRequisicionRepository;
use Infrastructure\Persistence\Adapter\MysqlReporteFallasRepository;
use Infrastructure\Persistence\Adapter\MysqlInventarioRepository;
use Infrastructure\Persistence\Adapter\MysqlTallerRepository;

class PreRequisicionController extends BaseController {

    public function listar() {
        $this->data['title'] = 'Listado de Pre-Requisiciones';

        $preRequisicionRepo = new MysqlPreRequisicionRepository();
        $getAllPreRequisicionesUseCase = new GetAllPreRequisicionesUseCase($preRequisicionRepo);
        $this->data['preRequisiciones'] = $getAllPreRequisicionesUseCase->ejecutar();

        $tallerRepo = new MysqlTallerRepository();
        $this->data['talleres'] = (new getAllTalleresUseCase($tallerRepo))->ejecutar();

        $data = $this->data;
        include __DIR__ . '/../Views/preRequisicion/listar.php';
    }

    public function crear() {
        $this->data['title'] = 'Crear Pre-Requisición de Materiales';

        $inventarioRepo = new MysqlInventarioRepository();
        $getAllInventarioUseCase = new GetAllInventarioUseCase($inventarioRepo);
        $inventarioCompleto = $getAllInventarioUseCase->ejecutar();

        $materialesFiltrados = array_filter($inventarioCompleto, function($item) {
            $esTipoCorrecto = in_array($item->getTipo(), ['Materia prima', 'Consumible']);
            return $esTipoCorrecto;
        });

        $this->data['materiales'] = $materialesFiltrados;

        $data = $this->data;
        include __DIR__ . '/../Views/preRequisicion/crear.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $umypf_n = $_POST['umypf_n'];
            $items = [];
            if (isset($_POST['materiales']) && is_array($_POST['materiales'])) {
                foreach ($_POST['materiales'] as $id_articulo => $cantidad) {
                    if (!empty($cantidad) && is_numeric($cantidad) && $cantidad > 0) {
                        $items[] = ['id_articulo' => $id_articulo, 'cantidad' => $cantidad];
                    }
                }
            }

            $repo = new MysqlPreRequisicionRepository();
            $useCase = new CrearPreRequisicionUseCase($repo);
            $useCase->ejecutar((int)$umypf_n, $items);

            $_SESSION['success'] = "Pre-requisición creada exitosamente.";
            header('Location: ' . base_url() . '/reporteFallas/editar/' . $umypf_n);
        }
    }
}
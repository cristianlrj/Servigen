<?php 
namespace Infrastructure\Web\Controllers;

use Infrastructure\Persistence\Adapter\MysqlUsuarioRepository;
use Infrastructure\Persistence\Adapter\MysqlRolRepository;

class BaseController {

    protected $usuario;
    protected $rol;
    protected $data = [];

    public function __construct() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ".base_url()."/auth/login");
            exit;
        }
        //Declaro el mensaje vacio al principio
        $this->data['mensaje'] = '';

        if (isset($_SESSION['success'])) {
            $this->data['mensaje'] = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                '.$_SESSION['success'].'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            unset($_SESSION['success']); // Limpiar para no mostrarlo de nuevo
        } elseif (isset($_SESSION['error'])) {
            $this->data['mensaje'] = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                '.$_SESSION['error'].'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            unset($_SESSION['error']); // Limpiar
        }

        $repoUsuario = new MysqlUsuarioRepository();
        $repoRol = new MysqlRolRepository();

        $this->usuario = $repoUsuario->obtenerUsuarioPorId($_SESSION['usuario_id']);
        $this->rol = $repoRol->buscarPorId($this->usuario->getRolId());

        // Variables comunes que puedes usar en todas las vistas
        $this->data['username'] = $this->usuario->getNombre()." ".$this->usuario->getApellido();
        $this->data['rolname'] = $this->rol->getNombreRol();
    }
}
<?php
namespace Infrastructure\Web\Controllers;

use Application\UseCases\Auth\LoginUseCase;
use Infrastructure\Persistence\Adapter\MysqlUsuarioRepository;
use Exception;

class AuthController {
    public function login() {
        session_start();

        if (isset($_SESSION['usuario_id'])) {
            header("Location: ".base_url()."/dashboard/index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreUsuario = $_POST['nombre_usuario'];
            $password = $_POST['password'];

            $repo = new MysqlUsuarioRepository();
            $loginUseCase = new LoginUseCase($repo);

            try {
                $usuario = $loginUseCase->ejecutar($nombreUsuario, $password);
                $_SESSION['usuario_id'] = $usuario->getId();
                header("Location: ".base_url()."/dashboard/index");
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
                $data['error'] = $error;
                require_once __DIR__ . "/../Views/auth/login.php";
            }
        } else {
            require_once __DIR__ . "/../Views/auth/login.php";
        }
    }

     public function LoginUser() {
        session_start();

        if (isset($_SESSION['usuario_id'])) {
            echo json_encode(["status" => true, "msg" => "Ya tiene una sesion activa."]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreUsuario = $_POST['nombre_usuario'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($nombreUsuario) || empty($password)) {
                echo json_encode(["status" => false, "msg" => "Usuario y clave son obligatorios."]);
                exit;
            }

            $repo = new MysqlUsuarioRepository();
            $loginUseCase = new LoginUseCase($repo);

            try {
                $usuario = $loginUseCase->ejecutar($nombreUsuario, $password);
                $_SESSION['usuario_id'] = $usuario->getId();
                echo json_encode(["status" => true, "msg" => "Inicio de sesion exitoso."]);
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
                $data['error'] = $error;
                echo json_encode(["status" => false, "msg" => $data['error']]);
            }
        } else {
            exit;
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ".base_url()."/auth/login");
        exit;
    }
}

<?php 
namespace Infrastructure\Web\Controllers;

use Infrastructure\Persistence\Adapter\MysqlUsuarioRepository;
use Application\UseCases\Usuario\RegistrarUsuarioUseCase;
use Application\UseCases\Usuario\getAllUsuariosUseCase;
use Application\UseCases\Usuario\getUsuarioUseCase;
use Application\UseCases\Usuario\EditarUsuarioUseCase;
use Application\UseCases\Usuario\EliminarUsuarioUseCase;

use Infrastructure\Persistence\Adapter\MysqlRolRepository;
use Application\UseCases\Rol\getAllRolesUseCase;

use Infrastructure\Persistence\Adapter\MysqlTallerRepository;
use Application\UseCases\Taller\getAllTalleresUseCase;

use Exception;

class UsuarioController extends BaseController {

    // Muestra la tabla de usuarios
    public function listar() {
        $repoUsuario = new MysqlUsuarioRepository();
        $getAllUsuariosUseCase = new getAllUsuariosUseCase($repoUsuario);
        $usuarios = $getAllUsuariosUseCase->ejecutar();
        $repoRol = new MysqlRolRepository();
        $getAllRolesUseCase = new getAllRolesUseCase($repoRol);
        $roles = $getAllRolesUseCase->ejecutar();

        $this->data['title'] = "SERVIGEN - Listar Usuarios";
        $this->data['usuarios'] = $usuarios;
        $this->data['roles'] = $roles;

        $data = $this->data;
        include __DIR__ . '/../Views/usuario/listar.php';
    }

    // Muestra el formulario de registro de usuarios
    public function crear() {
        $repoRol = new MysqlRolRepository();
        $getAllRolesUseCase = new getAllRolesUseCase($repoRol);

        $repoTaller = new MysqlTallerRepository();
        $getAllTalleresUseCase = new getAllTalleresUseCase($repoTaller);
        
        $roles = $getAllRolesUseCase->ejecutar();
        $talleres = $getAllTalleresUseCase->ejecutar();

        $this->data['title'] = "SERVIGEN - Crear Usuario";
        $this->data['roles'] = $roles;
        $this->data['talleres'] = $talleres;


        $data = $this->data;
        include __DIR__ . '/../Views/usuario/crear.php';
    }

    // Procesa el formulario de registro
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $repoUsuario = new MysqlUsuarioRepository();
            $id = $_POST['id'];
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $usuario = $_POST['usuario'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm-password'] ?? '';
            $rolId = isset($_POST['rol']) ? (int)$_POST['rol'] : null;
            $tallerId = !empty($_POST['taller']) ? (int)$_POST['taller'] : null;

            if($id == 0) {
                  // Validación básica
            if (!$nombre || !$apellido || !$usuario || !$email || !$password || !$rolId) {
                $_SESSION['error'] = "Todos los campos obligatorios deben ser completados.";
                header('Location:'.base_url().'/usuario/crear');
                return;
            }

            if($password != $confirmPassword) {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
                header('Location:'.base_url().'/usuario/crear');
                return;
            }
                try {      
                    $registrarUsuarioUseCase = new RegistrarUsuarioUseCase($repoUsuario);
                    $registrarUsuarioUseCase->ejecutar($nombre,
                    $apellido,
                    $usuario,
                    $email,
                    $password,
                    $rolId,
                    $tallerId);
    
                    $_SESSION['success'] = "Usuario registrado correctamente.";
                    unset($_SESSION['error']);
                    header('Location:'.base_url().'/usuario/listar');
                } catch (Exception $e) {
                    $_SESSION['error'] = "Error al registrar usuario: " . $e->getMessage();
                    header('Location:'.base_url().'/usuario/crear');
                }
            } else {
                // Validación básica
                if (!$nombre || !$apellido || !$usuario || !$email || !$rolId) {
                    $_SESSION['error'] = "Todos los campos obligatorios deben ser completados.";
                    header('Location:'.base_url().'/usuario/editar');
                    return;
                }

                try {
                    $editarUsuarioUseCase = new EditarUsuarioUseCase($repoUsuario);
                    $editarUsuarioUseCase->ejecutar($id,
                    $nombre,
                    $apellido,
                    $usuario,
                    $email,
                    $password,
                    $rolId,
                    $tallerId);
    
                    $_SESSION['success'] = "Usuario editado correctamente.";
                    unset($_SESSION['error']);
                    header('Location:'.base_url().'/usuario/listar');
                } catch (Exception $e) {
                    $_SESSION['error'] = "Error al editar usuario: " . $e->getMessage();
                    header('Location:'.base_url().'/usuario/editar');
                }
            }

            
        }
    }

    //editar
    public function editar($id) {
        $repoUsuario = new MysqlUsuarioRepository();
        $repoRol = new MysqlRolRepository();
        $getAllRolesUseCase = new getAllRolesUseCase($repoRol);
        $roles = $getAllRolesUseCase->ejecutar();
        $repoTaller = new MysqlTallerRepository();
        $getAllTalleresUseCase = new getAllTalleresUseCase($repoTaller);
        $talleres = $getAllTalleresUseCase->ejecutar();
        $getUsuarioUseCase = new getUsuarioUseCase($repoUsuario);
        $usuario = $getUsuarioUseCase->ejecutar($id);

        $this->data['title'] = "SERVIGEN - Editar Usuario";
        $this->data['roles'] = $roles;
        $this->data['talleres'] = $talleres;
        $this->data['usuario'] = $usuario;

        $data = $this->data;
        include __DIR__ . '/../Views/usuario/editar.php';
    }   

    public function eliminar($id) {
        // 1. Instanciar Repositorio y Caso de Uso
        $repoUsuario = new MysqlUsuarioRepository();
        $eliminarUsuarioUseCase = new EliminarUsuarioUseCase($repoUsuario);

        try {
            // 2. Ejecutar la acción
            $eliminarUsuarioUseCase->ejecutar((int)$id);
            
            $_SESSION['success'] = "Usuario eliminado correctamente.";
            unset($_SESSION['error']);

        } catch (Exception $e) {

             $_SESSION['error'] = "Error al eliminar usuario: " . $e->getMessage();
        }

        // 3. Redirigir de vuelta a la lista de usuarios
        // (Ajusta esta URL a tu ruta de "listar usuarios")
        header('Location:'.base_url().'/usuario/listar');
        exit;
    }
}
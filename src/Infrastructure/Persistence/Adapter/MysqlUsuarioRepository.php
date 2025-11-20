<?php
namespace Infrastructure\Persistence\Adapter;

use Domain\Repositories\UsuarioRepositoryInterface;
use Domain\Entities\Usuario;
use PDO;

class MysqlUsuarioRepository extends MysqlPersistenceAdapter implements UsuarioRepositoryInterface {

    public function buscarPorNombreUsuario(string $nombreUsuario): ?Usuario {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE usuario_usuario = :usuario_usuario LIMIT 1");
        $stmt->execute(['usuario_usuario' => $nombreUsuario]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Usuario(
            (int)$row['id_usuario'],
            $row['usuario_nombre'],
            $row['usuario_apellido'],
            $row['usuario_usuario'],
            $row['usuario_email'],
            $row['usuario_clave'],
            (int)$row['id_rol'],
            $row['taller'] !== null ? (int)$row['taller'] : null
        );
    }

    public function obtenerUsuarioPorId(int $id): ?Usuario {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id_usuario = :id_usuario LIMIT 1");
        $stmt->execute(['id_usuario' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Usuario(
            (int)$row['id_usuario'],
            $row['usuario_nombre'],
            $row['usuario_apellido'],
            $row['usuario_usuario'],
            $row['usuario_email'],
            $row['usuario_clave'],
            (int)$row['id_rol'],
            $row['taller'] !== null ? (int)$row['taller'] : null
        ) : null;
    }

    public function guardar(Usuario $usuario): Usuario {
        $stmt = $this->conn->prepare(
            "INSERT INTO usuario (usuario_nombre, usuario_apellido, usuario_usuario, usuario_email, usuario_clave, id_rol, taller) 
             VALUES (:usuario_nombre, :usuario_apellido, :usuario_usuario, :usuario_email, :usuario_clave, :id_rol, :taller)"
        );
        $stmt->execute([
            'usuario_nombre' => $usuario->getNombre(),
            'usuario_apellido' => $usuario->getApellido(),
            'usuario_usuario' => $usuario->getNombreUsuario(),
            'usuario_email' => $usuario->getEmail(),
            'usuario_clave' => $usuario->getHashedPassword(),
            'id_rol' => $usuario->getRolId(),
            'taller' => $usuario->getTallerId(),
        ]);
        $id = $this->conn->lastInsertId();

        return new Usuario(
            $id,
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getNombreUsuario(),
            $usuario->getEmail(),
            $usuario->getHashedPassword(),
            $usuario->getRolId(),
            $usuario->getTallerId()
        );
    }

    public function editar(Usuario $usuario): Usuario {
        $stmt = $this->conn->prepare(
            "UPDATE usuario 
             SET usuario_nombre = :usuario_nombre, 
                 usuario_apellido = :usuario_apellido, 
                 usuario_usuario = :usuario_usuario, 
                 usuario_email = :usuario_email, 
                 usuario_clave = :usuario_clave, 
                 id_rol = :id_rol, 
                 taller = :taller 
             WHERE id_usuario = :id_usuario"
        );
        $stmt->execute([
            'usuario_nombre' => $usuario->getNombre(),
            'usuario_apellido' => $usuario->getApellido(),
            'usuario_usuario' => $usuario->getNombreUsuario(),
            'usuario_email' => $usuario->getEmail(),
            'usuario_clave' => $usuario->getHashedPassword(),
            'id_rol' => $usuario->getRolId(),
            'taller' => $usuario->getTallerId(),
            'id_usuario' => $usuario->getId(),
        ]);
        return $usuario;
    }

    public function getAll(): array {
        $stmt = $this->conn->prepare("SELECT * FROM usuario");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($row) {
            return new Usuario(
                (int)$row['id_usuario'],
                $row['usuario_nombre'],
                $row['usuario_apellido'],
                $row['usuario_usuario'],
                $row['usuario_email'],
                $row['usuario_clave'],
                (int)$row['id_rol'],
                $row['taller'] !== null ? (int)$row['taller'] : null
            );
        }, $rows);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM usuario WHERE id_usuario = :id_usuario");
        
        // execute() devuelve true si fue exitoso
        return $stmt->execute(['id_usuario' => $id]);
    }

}

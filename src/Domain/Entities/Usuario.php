<?php
namespace Domain\Entities;
class Usuario {
    private ?int $id;
    private string $nombre;
    private string $apellido;
    private string $nombreUsuario;
    private string $email;
    private string $hashedPassword;
    private int $rolId;
    private ?int $tallerId; // null si no pertenece a ningún taller

    public function __construct(
        ?int $id = null,
        string $nombre,
        string $apellido,
        string $nombreUsuario,
        string $email,
        string $hashedPassword,
        int $rolId,
        ?int $tallerId = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->nombreUsuario = $nombreUsuario;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
        $this->rolId = $rolId;
        $this->tallerId = $tallerId;
    }

    public static function crearNuevo(
        string $nombre,
        string $apellido,
        string $nombreUsuario,
        string $email,
        string $password,
        int $rolId,
        ?int $tallerId = null
    ): self {
        //Colocar la contraseña hasheada en sha256
        //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return new self(null, $nombre, $apellido, $nombreUsuario, $email, $password, $rolId, $tallerId);
    }

    public function actualizar(
        string $nombre,
        string $apellido,
        string $nombreUsuario,
        string $email,
        string $password,
        int $rolId,
        ?int $tallerId = null
    ): void {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->nombreUsuario = $nombreUsuario;
        $this->email = $email;
        $this->hashedPassword = $password;
        $this->rolId = $rolId;
        $this->tallerId = $tallerId;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getApellido(): string { return $this->apellido; }
    public function getNombreUsuario(): string { return $this->nombreUsuario; }
    public function getEmail(): string { return $this->email; }
    public function getHashedPassword(): string { return $this->hashedPassword; }
    public function getRolId(): int { return $this->rolId; }
    public function getTallerId(): ?int { return $this->tallerId; }
}

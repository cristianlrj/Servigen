<?php
namespace Domain\Entities;

class ReporteFallas {
    
    // --- Propiedades de la tabla Maestra (reporte_fallas) ---
    private int $umypf_n;
    private int $id_usuario;
    private string $fecha_creacion;
    private int $id_taller;
    private string $unidad_solicitante;
    private string $persona_contacto;
    private ?string $email_contacto; // Nueva propiedad para el email de contacto
    private string $descripcion;
    private string $estado;
    private string $fecha_ultimo_estado;
    
    // Asumo que estas columnas están en la tabla maestra
    private ?int $obrero_asignado_id = null; 
    private ?string $prioridad = null; 
    private ?int $satisfaccion = null; // Nueva propiedad para la satisfacción
    private ?string $comentarios_satisfaccion = null; // Nueva propiedad para los comentarios de satisfacción

    private array $historial = []; // Almacena el log de DescripcionFalla

    public function __construct(
        int $umypf_n,
        int $id_usuario,
        string $fecha_creacion,
        int $id_taller,
        string $unidad_solicitante,
        string $persona_contacto,
        ?string $email_contacto, // Añadir email_contacto al constructor
        string $descripcion,
        string $estado,
        string $fecha_ultimo_estado
    ) {
        $this->umypf_n = $umypf_n;
        $this->id_usuario = $id_usuario;
        $this->fecha_creacion = $fecha_creacion;
        $this->id_taller = $id_taller;
        $this->unidad_solicitante = $unidad_solicitante;
        $this->persona_contacto = $persona_contacto;
        $this->email_contacto = $email_contacto; // Asignar el email de contacto
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->fecha_ultimo_estado = $fecha_ultimo_estado;
    }

    // --- Métodos de Lógica de Negocio (Dominio) ---

    public function setHistorial(array $historial): void {
        $this->historial = $historial;
        // Aseguramos que el historial esté ordenado por fecha
        usort($this->historial, fn($a, $b) => $a->getFecha() <=> $b->getFecha());
    }

    public function agregarEntradaLog(DescripcionFalla $entrada): void {
        $this->historial[] = $entrada;
    }

    public function asignarObrero(?int $obrero_id): void {
        $this->obrero_asignado_id = $obrero_id;
    }

    public function setPrioridad(?string $prioridad): void {
        $this->prioridad = $prioridad;
    }

    public function setSatisfaccion(?int $satisfaccion): void {
        $this->satisfaccion = $satisfaccion;
    }

    public function setComentariosSatisfaccion(?string $comentarios): void {
        $this->comentarios_satisfaccion = $comentarios;
    }



    // --- Getters de Acceso ---

    public function getId(): int {
        return $this->umypf_n;
    }

    public function getUsuarioId(): int {
        return $this->id_usuario;
    }

    public function getFechaCreacion(): string {
        return $this->fecha_creacion;
    }

    public function getIdTaller(): int {
        return $this->id_taller;
    }
    
    public function getUnidadSolicitante(): string {
        return $this->unidad_solicitante;
    }

    public function getPersonaContacto(): string {
        return $this->persona_contacto;
    }

    public function getEmailContacto(): ?string {
        return $this->email_contacto;
    }

    public function getObreroAsignadoId(): ?int {
        return $this->obrero_asignado_id;
    }

    public function getPrioridad(): ?string {
        return $this->prioridad;
    }

    public function getHistorial(): array {
        return $this->historial;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getFechaUltimoEstado(): string {
        return $this->fecha_ultimo_estado;
    }

    public function getSatisfaccion(): ?int {
        return $this->satisfaccion;
    }

    public function getComentariosSatisfaccion(): ?string {
        return $this->comentarios_satisfaccion;
    }

    public function getDescripcionInicial(): string {
        if (empty($this->historial)) return 'N/A';
        return $this->historial[0]->getDescripcion();
    }

    public function getEstadoActual(): string {
        if (empty($this->historial)) return 'N/A';
        return end($this->historial)->getEstado();
    }

    public function getDescripcionActual(): string {
        if (empty($this->historial)) return 'N/A';
        return end($this->historial)->getDescripcion();
    }
}
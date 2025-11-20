<?php
namespace Application\UseCases\ReporteFallas;

use Domain\Repositories\ReporteFallasRepositoryInterface;

class GuardarTokenSatisfaccionUseCase {
    private $repository;

    public function __construct(ReporteFallasRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Genera un token de satisfacción, lo guarda en la BD y lo devuelve.
     *
     * @param int $reporteId El ID del reporte de falla.
     * @return string El token generado.
     * @throws \Exception Si ocurre un error.
     */
    public function ejecutar(int $reporteId): string {
        // 1. Generar un token seguro y único
        $token = bin2hex(random_bytes(32));

        // 2. Definir la fecha de expiración (ej. 7 días a partir de ahora)
        $expiresAt = (new \DateTime())
            ->modify('+7 days')
            ->format('Y-m-d H:i:s');

        // 3. Persistir el token y la fecha en la base de datos
        $this->repository->guardarTokenSatisfaccion($reporteId, $token, $expiresAt);

        // 4. Devolver el token para que pueda ser usado en el correo
        return $token;
    }
}
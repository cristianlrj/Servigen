<?php
namespace Application\UseCases\Obrero;

use Domain\Services\ObreroApiServiceInterface;
use Domain\Entities\Obrero;

class ConsultObreroByCedula {
    private ObreroApiServiceInterface $api;

    public function __construct(ObreroApiServiceInterface $api) {
        $this->api = $api;
    }

    public function ejecutar(string $cedula, string $token): ?Obrero {
        return $this->api->consultarPorCedula($cedula, $token);
    }
}
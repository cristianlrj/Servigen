<?php
namespace Domain\Services;

use Domain\Entities\Obrero;

interface ObreroApiServiceInterface {
    public function consultarPorCedula(string $cedula, string $token): ?Obrero;
}
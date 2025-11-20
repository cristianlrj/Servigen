<?php 
namespace Application\UseCases\Rol;

use Domain\Repositories\RolRepositoryInterface;
use Domain\Entities\Rol;

class GetRolUseCase {
    private RolRepositoryInterface $rolRepository;

    public function __construct(RolRepositoryInterface $rolRepository) {
        $this->rolRepository = $rolRepository;
    }

    public function ejecutar(int $rolId): ?Rol {
        return $this->rolRepository->buscarPorId($rolId);
    }
}
?>
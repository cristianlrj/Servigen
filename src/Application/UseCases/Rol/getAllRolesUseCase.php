<?php 
namespace Application\UseCases\Rol;

use Domain\Repositories\RolRepositoryInterface;
use Domain\Entities\Rol;

class GetAllRolesUseCase {
    private RolRepositoryInterface $rolRepository;

    public function __construct(RolRepositoryInterface $rolRepository) {
        $this->rolRepository = $rolRepository;
    }

    public function ejecutar(): array {
        return $this->rolRepository->obtenerTodos();
    }
}
?>
<?php
namespace Infrastructure\Web\Controllers;

class ErrorsController {
    public function error404(string $mensaje = 'Página no encontrada') {
        http_response_code(404);
        // Aquí puedes cargar una vista personalizada si tienes
        include __DIR__ . '/../Views/errors/404.php';
    }

    public function error500(string $mensaje = 'Error interno del servidor') {
        http_response_code(500);
        echo "<h1>Error 500</h1>";
        echo "<p>$mensaje</p>";
    }
}
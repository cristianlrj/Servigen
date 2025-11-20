<?php
namespace Infrastructure\Routing;

use Infrastructure\Web\Controllers\ErrorsController;

class Router {
    public function handle(string $url) {
        $parts = explode('/', $url);
        $controllerName = $parts[0] ?? null;
        $method = $parts[1] ?? null;
        $params = $parts[2] ?? null;

        // Collect all parts after the method as parameters
        // If there are parameters, implode them into a single string
        if (count($parts) > 3) {
            for ($i=3; $i < count($parts); $i++) { 
                $params .= ",".$parts[$i];
                
            }
        }


        $errorsController = new ErrorsController();

        if (!$controllerName || !$method) {
            $errorsController->error404("URL incompleta o mal formada.");
            return;
        }

        $controllerClass = 'Infrastructure\\Web\\Controllers\\' . ucfirst($controllerName) . 'Controller';

        if (!class_exists($controllerClass)) {
            $errorsController->error404("El controlador '$controllerName' no existe.");
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            $errorsController->error404("El método '$method' no existe en el controlador '$controllerName'.");
            return;
        }

        call_user_func_array([$controller, $method], [$params]);
    }
}
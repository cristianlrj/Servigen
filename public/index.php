<?php
require_once '../src/Shared/autoload.php';
require_once '../src/Shared/config.php';
require_once '../src/Shared/helpers.php';
require_once '../src/Shared/vendor/autoload.php';

use Infrastructure\Routing\Router;

$router = new Router();
$router->handle(!empty($_GET['url']) ? $_GET['url'] : 'auth/login');
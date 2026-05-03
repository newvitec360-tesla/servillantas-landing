<?php
require __DIR__ . '/../../config/bootstrap.php';

use App\Core\Router;

$routes = require ROOT_PATH . '/config/routes_mobile.php';
$path = $_GET['r'] ?? '/';
(new Router($routes))->dispatch($path, $_SERVER['REQUEST_METHOD']);

<?php

// Servillantas MVC — Root Bootstrap para Shared Hosting (cPanel)
// En shared hosting: public_html/ es webroot Y raiz del framework
// ROOT_PATH = __DIR__ del index.php que incluye este bootstrap
// Esto resuelve: public_html/config/bootstrap.php → ROOT = public_html/
require __DIR__ . '/config/bootstrap.php';

// Deteccion de dispositivo simple
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isMobile = preg_match('/Mobile|Android|iPhone|iPod/i', $userAgent);
$platform = $isMobile ? 'mobile' : 'desktop';

// Cargar rutas y router
use App\Core\Router;

$routeFile = ROOT_PATH . "/config/routes_{$platform}.php";
$routes = require $routeFile;
$path = $_GET['r'] ?? '/';
(new Router($routes))->dispatch($path, $_SERVER['REQUEST_METHOD']);

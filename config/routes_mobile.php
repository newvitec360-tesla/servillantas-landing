<?php

use App\Controllers\Mobile\DashboardController;
use App\Controllers\Mobile\ClientesController;
use App\Controllers\Mobile\CarteraController;
use App\Controllers\Mobile\DeudorController;

return [
    'GET' => [
        '/login' => [\App\Controllers\Mobile\AuthController::class, 'showLoginForm'],
        '/' => [\App\Controllers\Mobile\PublicLandingController::class, 'index'],
        '/dashboard' => [DashboardController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/clientes' => [ClientesController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/cartera' => [CarteraController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/deudor' => [DeudorController::class, 'index'],
    ],
    'POST' => [
        '/login' => [\App\Controllers\Mobile\AuthController::class, 'login', 'middlewares' => [\App\Middlewares\CsrfMiddleware::class]],
        '/logout' => [\App\Controllers\Mobile\AuthController::class, 'logout', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
    ]
];

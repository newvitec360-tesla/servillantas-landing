<?php

use App\Controllers\Desktop\DashboardController;
use App\Controllers\Desktop\ClientesController;
use App\Controllers\Desktop\CarteraController;
use App\Controllers\Desktop\ExpedientesController;
use App\Controllers\Desktop\CampanasController;
use App\Controllers\Desktop\PagosController;
use App\Controllers\Desktop\ReportesController;
use App\Controllers\Desktop\ConfiguracionController;
use App\Controllers\Desktop\PlantillasController;
use App\Controllers\Desktop\LandingApiController;

return [
    'GET' => [
        '/login' => [\App\Controllers\Desktop\AuthController::class, 'showLoginForm'],
        '/' => [\App\Controllers\Desktop\PublicLandingController::class, 'index'],
        '/dashboard' => [DashboardController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        // Clientes CRUD
        '/clientes' => [ClientesController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/clientes/show' => [ClientesController::class, 'show', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/clientes/create' => [ClientesController::class, 'create', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/clientes/edit' => [ClientesController::class, 'edit', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        // Cartera CRUD
        '/cartera' => [CarteraController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/cartera/show' => [CarteraController::class, 'show', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/cartera/create' => [CarteraController::class, 'create', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/cartera/edit' => [CarteraController::class, 'edit', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        // Expedientes / Gestiones CRUD
        '/expedientes' => [ExpedientesController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/expedientes/ficha' => [ExpedientesController::class, 'ficha', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/expedientes/create' => [ExpedientesController::class, 'create', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        // Campañas CRUD
        '/campanas' => [CampanasController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/campanas/show' => [CampanasController::class, 'show', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/campanas/create' => [CampanasController::class, 'create', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/campanas/edit' => [CampanasController::class, 'edit', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        // Pagos CRUD
        '/pagos' => [PagosController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/pagos/show' => [PagosController::class, 'show', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/pagos/create' => [PagosController::class, 'create', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/reportes' => [ReportesController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        // Plantillas CRUD
        '/plantillas' => [PlantillasController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/plantillas/create' => [PlantillasController::class, 'create', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/plantillas/edit' => [PlantillasController::class, 'edit', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/configuracion' => [ConfiguracionController::class, 'index', 'middlewares' => [\App\Middlewares\AuthMiddleware::class]],
        '/admin-landing' => [\App\Controllers\Desktop\AdminLandingController::class, 'index', 'middlewares' => [\App\Middlewares\LandingAuthMiddleware::class]],
        '/admin-landing/login' => [\App\Controllers\Desktop\AdminLandingController::class, 'showLogin'],
        '/admin-landing/logout' => [\App\Controllers\Desktop\AdminLandingController::class, 'logout'],
        
        // API Landing Gestor (rutas sin /api/admin/ para evitar bloqueo de ModSecurity)
        '/landing-gestor/borrador' => [LandingApiController::class, 'getDraft', 'middlewares' => [\App\Middlewares\LandingAuthMiddleware::class]],
        '/landing-gestor/health' => [LandingApiController::class, 'healthCheck'],
    ],
    'POST' => [
        '/admin-landing/login' => [\App\Controllers\Desktop\AdminLandingController::class, 'login'],
        '/login' => [\App\Controllers\Desktop\AuthController::class, 'login', 'middlewares' => [\App\Middlewares\CsrfMiddleware::class]],
        '/logout' => [\App\Controllers\Desktop\AuthController::class, 'logout', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Clientes CRUD mutations
        '/clientes/store' => [ClientesController::class, 'store', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/clientes/update' => [ClientesController::class, 'update', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/clientes/delete' => [ClientesController::class, 'delete', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Cartera CRUD mutations
        '/cartera/store' => [CarteraController::class, 'store', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/cartera/update' => [CarteraController::class, 'update', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Pagos mutations
        '/pagos/store' => [PagosController::class, 'store', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/pagos/validate' => [PagosController::class, 'validatePago', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/pagos/reject' => [PagosController::class, 'rejectPago', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Gestiones mutations
        '/expedientes/store' => [ExpedientesController::class, 'store', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Campañas mutations
        '/campanas/store' => [CampanasController::class, 'store', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/campanas/update' => [CampanasController::class, 'update', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Plantillas mutations
        '/plantillas/store' => [PlantillasController::class, 'store', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/plantillas/update' => [PlantillasController::class, 'update', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Configuración — CRUD Usuarios
        '/configuracion/usuarios/store' => [ConfiguracionController::class, 'storeUsuario', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/usuarios/update' => [ConfiguracionController::class, 'updateUsuario', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/usuarios/toggle' => [ConfiguracionController::class, 'toggleUsuario', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/usuarios/delete' => [ConfiguracionController::class, 'deleteUsuario', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/usuarios/reset-password' => [ConfiguracionController::class, 'resetPassword', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Configuración — CRUD Roles + Permisos
        '/configuracion/roles/store' => [ConfiguracionController::class, 'storeRol', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/roles/update' => [ConfiguracionController::class, 'updateRol', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/roles/delete' => [ConfiguracionController::class, 'deleteRol', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/permisos/sync' => [ConfiguracionController::class, 'syncPermisos', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        // Configuración — General + Políticas
        '/configuracion/general/save' => [ConfiguracionController::class, 'saveGeneral', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/politicas/store' => [ConfiguracionController::class, 'storePolitica', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/politicas/update' => [ConfiguracionController::class, 'updatePolitica', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        '/configuracion/politicas/delete' => [ConfiguracionController::class, 'deletePolitica', 'middlewares' => [\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class]],
        
        // API Landing Gestor mutations (rutas sin /api/admin/ para evitar bloqueo de ModSecurity)
        '/landing-gestor/borrador' => [LandingApiController::class, 'saveDraft', 'middlewares' => [\App\Middlewares\LandingAuthMiddleware::class]],
        '/landing-gestor/publicar' => [LandingApiController::class, 'publish', 'middlewares' => [\App\Middlewares\LandingAuthMiddleware::class]],
    ]
];

<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Services\PlantillaService;

final class PlantillasController extends Controller
{
    private PlantillaService $service;

    public function __construct()
    {
        $this->service = new PlantillaService();
    }

    /**
     * GET /plantillas — Template listing
     */
    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $canal = trim($_GET['canal'] ?? '');
        $estado = trim($_GET['estado'] ?? '');

        $plantillas = $this->service->listar($query, $canal, $estado);
        $porCanal = $this->service->countByCanal();

        $this->view('desktop/plantillas/index', [
            'title' => 'Plantillas de Mensajes',
            'variant' => 'desktop',
            'plantillas' => $plantillas,
            'porCanal' => $porCanal,
            'filters' => ['q' => $query, 'canal' => $canal, 'estado' => $estado],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /plantillas/create
     */
    public function create(): void
    {
        $this->view('desktop/plantillas/create', [
            'title' => 'Nueva Plantilla',
            'variant' => 'desktop',
            'plantilla' => [],
            'errors' => [],
        ], 'desktop/layouts/app');
    }

    /**
     * POST /plantillas/store
     */
    public function store(): void
    {
        $result = $this->service->crear($_POST);
        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Plantilla creada exitosamente.'];
            header('Location: ' . route_url('/plantillas', 'desktop'));
            exit;
        }

        $this->view('desktop/plantillas/create', [
            'title' => 'Nueva Plantilla',
            'variant' => 'desktop',
            'plantilla' => $_POST,
            'errors' => $result['errors'],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /plantillas/edit&id=X
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $plantilla = $this->service->obtener($id);
        if (!$plantilla) {
            header('Location: ' . route_url('/plantillas', 'desktop'));
            exit;
        }

        $this->view('desktop/plantillas/create', [
            'title' => 'Editar Plantilla',
            'variant' => 'desktop',
            'plantilla' => $plantilla,
            'errors' => [],
            'editing' => true,
        ], 'desktop/layouts/app');
    }

    /**
     * POST /plantillas/update
     */
    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/plantillas', 'desktop'));
            exit;
        }

        $result = $this->service->actualizar($id, $_POST);
        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Plantilla actualizada.'];
            header('Location: ' . route_url('/plantillas', 'desktop'));
            exit;
        }

        $this->view('desktop/plantillas/create', [
            'title' => 'Editar Plantilla',
            'variant' => 'desktop',
            'plantilla' => array_merge($_POST, ['id' => $id]),
            'errors' => $result['errors'],
            'editing' => true,
        ], 'desktop/layouts/app');
    }
}

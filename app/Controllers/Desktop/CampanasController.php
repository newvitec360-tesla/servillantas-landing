<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Services\CampanaService;

final class CampanasController extends Controller
{
    private CampanaService $service;

    public function __construct()
    {
        $this->service = new CampanaService();
    }

    /**
     * GET /campanas — Campaign listing with KPIs
     */
    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $estado = trim($_GET['estado'] ?? '');
        $canal = trim($_GET['canal'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));

        $result = $this->service->listar($query, $estado, $canal, $page);
        $kpis = $this->service->kpis();

        $this->view('desktop/campanas/index', [
            'title' => 'Campañas y Automatización',
            'variant' => 'desktop',
            'campanas' => $result['data'],
            'pagination' => $result,
            'kpis' => $kpis,
            'filters' => ['q' => $query, 'estado' => $estado, 'canal' => $canal],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /campanas/show&id=X — Campaign detail with messages
     */
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/campanas', 'desktop'));
            exit;
        }

        $campana = $this->service->obtener($id);
        if (!$campana) {
            http_response_code(404);
            $this->view('errors/404', ['path' => '/campanas/show']);
            return;
        }

        $this->view('desktop/campanas/show', [
            'title' => 'Campaña: ' . $campana['nombre'],
            'variant' => 'desktop',
            'campana' => $campana,
        ], 'desktop/layouts/app');
    }

    /**
     * GET /campanas/create — New campaign form
     */
    public function create(): void
    {
        $plantillas = $this->service->plantillas();

        $this->view('desktop/campanas/create', [
            'title' => 'Nueva Campaña',
            'variant' => 'desktop',
            'campana' => [],
            'plantillas' => $plantillas,
            'errors' => [],
        ], 'desktop/layouts/app');
    }

    /**
     * POST /campanas/store — Persist campaign
     */
    public function store(): void
    {
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        $result = $this->service->crear($_POST, $userId);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Campaña creada exitosamente.'];
            header('Location: ' . route_url('/campanas', 'desktop'));
            exit;
        }

        $plantillas = $this->service->plantillas();

        $this->view('desktop/campanas/create', [
            'title' => 'Nueva Campaña',
            'variant' => 'desktop',
            'campana' => $_POST,
            'plantillas' => $plantillas,
            'errors' => $result['errors'],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /campanas/edit&id=X — Edit campaign
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $campana = $this->service->obtener($id);
        if (!$campana) {
            header('Location: ' . route_url('/campanas', 'desktop'));
            exit;
        }

        $plantillas = $this->service->plantillas();

        $this->view('desktop/campanas/create', [
            'title' => 'Editar Campaña',
            'variant' => 'desktop',
            'campana' => $campana,
            'plantillas' => $plantillas,
            'errors' => [],
            'editing' => true,
        ], 'desktop/layouts/app');
    }

    /**
     * POST /campanas/update — Update campaign
     */
    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/campanas', 'desktop'));
            exit;
        }

        $result = $this->service->actualizar($id, $_POST);
        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Campaña actualizada.'];
            header('Location: ' . route_url('/campanas/show', 'desktop') . '&id=' . $id);
            exit;
        }

        $plantillas = $this->service->plantillas();

        $this->view('desktop/campanas/create', [
            'title' => 'Editar Campaña',
            'variant' => 'desktop',
            'campana' => array_merge($_POST, ['id' => $id]),
            'plantillas' => $plantillas,
            'errors' => $result['errors'],
            'editing' => true,
        ], 'desktop/layouts/app');
    }
}

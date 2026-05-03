<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Services\CarteraService;

final class CarteraController extends Controller
{
    private CarteraService $service;

    public function __construct()
    {
        $this->service = new CarteraService();
    }

    /**
     * GET /cartera — List with filters, aging, donut, KPIs
     */
    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $riesgo = trim($_GET['riesgo'] ?? '');
        $estado = trim($_GET['estado'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));

        $result = $this->service->listar($query, $riesgo, $estado, $page);
        $kpis = $this->service->kpis();

        $this->view('desktop/cartera/index', [
            'title' => 'Gestión de Cartera',
            'variant' => 'desktop',
            'obligaciones' => $result['data'],
            'pagination' => $result,
            'kpis' => $kpis,
            'filters' => ['q' => $query, 'riesgo' => $riesgo, 'estado' => $estado],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /cartera/show&id=X — Obligation detail
     */
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/cartera', 'desktop'));
            exit;
        }

        $obligacion = $this->service->obtener($id);
        if (!$obligacion) {
            http_response_code(404);
            $this->view('errors/404', ['path' => '/cartera/show']);
            return;
        }

        $this->view('desktop/cartera/show', [
            'title' => 'Obligación ' . $obligacion['codigo_interno'],
            'variant' => 'desktop',
            'obligacion' => $obligacion,
        ], 'desktop/layouts/app');
    }

    /**
     * GET /cartera/create — New obligation form
     */
    public function create(): void
    {
        // Load clients for dropdown
        $clienteRepo = new \App\Repositories\ClienteRepository();
        $clientes = $clienteRepo->findAll(500, 0);

        $this->view('desktop/cartera/create', [
            'title' => 'Nueva Obligación',
            'variant' => 'desktop',
            'obligacion' => [],
            'clientes' => $clientes,
            'errors' => [],
        ], 'desktop/layouts/app');
    }

    /**
     * POST /cartera/store — Create obligation
     */
    public function store(): void
    {
        $result = $this->service->crear($_POST);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Obligación creada exitosamente.'];
            header('Location: ' . route_url('/cartera', 'desktop'));
            exit;
        }

        $clienteRepo = new \App\Repositories\ClienteRepository();
        $clientes = $clienteRepo->findAll(500, 0);

        $this->view('desktop/cartera/create', [
            'title' => 'Nueva Obligación',
            'variant' => 'desktop',
            'obligacion' => $_POST,
            'clientes' => $clientes,
            'errors' => $result['errors'],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /cartera/edit&id=X — Edit obligation form
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $obligacion = $this->service->obtener($id);
        if (!$obligacion) {
            header('Location: ' . route_url('/cartera', 'desktop'));
            exit;
        }

        $clienteRepo = new \App\Repositories\ClienteRepository();
        $clientes = $clienteRepo->findAll(500, 0);

        $this->view('desktop/cartera/create', [
            'title' => 'Editar Obligación',
            'variant' => 'desktop',
            'obligacion' => $obligacion,
            'clientes' => $clientes,
            'errors' => [],
            'editing' => true,
        ], 'desktop/layouts/app');
    }

    /**
     * POST /cartera/update — Update obligation
     */
    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/cartera', 'desktop'));
            exit;
        }

        $result = $this->service->actualizar($id, $_POST);
        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Obligación actualizada.'];
            header('Location: ' . route_url('/cartera/show', 'desktop') . '&id=' . $id);
            exit;
        }

        $clienteRepo = new \App\Repositories\ClienteRepository();
        $clientes = $clienteRepo->findAll(500, 0);

        $this->view('desktop/cartera/create', [
            'title' => 'Editar Obligación',
            'variant' => 'desktop',
            'obligacion' => array_merge($_POST, ['id' => $id]),
            'clientes' => $clientes,
            'errors' => $result['errors'],
            'editing' => true,
        ], 'desktop/layouts/app');
    }
}

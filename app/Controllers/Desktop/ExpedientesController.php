<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Services\GestionService;
use App\Services\ClienteService;

final class ExpedientesController extends Controller
{
    private GestionService $gestionService;
    private ClienteService $clienteService;

    public function __construct()
    {
        $this->gestionService = new GestionService();
        $this->clienteService = new ClienteService();
    }

    /**
     * GET /expedientes — Gestiones listing with KPIs
     */
    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $canal = trim($_GET['canal'] ?? '');
        $resultado = trim($_GET['resultado'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));

        $result = $this->gestionService->listar($query, $canal, $resultado, $page);
        $kpis = $this->gestionService->kpis();

        $this->view('desktop/expedientes/index', [
            'title' => 'Gestiones de Cobranza',
            'variant' => 'desktop',
            'gestiones' => $result['data'],
            'pagination' => $result,
            'kpis' => $kpis,
            'filters' => ['q' => $query, 'canal' => $canal, 'resultado' => $resultado],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /expedientes/ficha&id=X — Client file with tabs + timeline
     */
    public function ficha(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/expedientes', 'desktop'));
            exit;
        }

        $cliente = $this->clienteService->obtener($id);
        if (!$cliente) {
            http_response_code(404);
            $this->view('errors/404', ['path' => '/expedientes/ficha']);
            return;
        }

        $gestiones = $this->gestionService->obtenerPorCliente($id);

        $this->view('desktop/expedientes/ficha', [
            'title' => 'Expediente: ' . $cliente['nombre_completo'],
            'variant' => 'desktop',
            'cliente' => $cliente,
            'gestiones' => $gestiones,
        ], 'desktop/layouts/app');
    }

    /**
     * GET /expedientes/create — New gestión form
     */
    public function create(): void
    {
        $clienteRepo = new \App\Repositories\ClienteRepository();
        $clientes = $clienteRepo->findAll(500, 0);
        $obligacionRepo = new \App\Repositories\ObligacionRepository();
        $obligaciones = $obligacionRepo->findAll(500, 0);

        $this->view('desktop/expedientes/create', [
            'title' => 'Registrar Gestión',
            'variant' => 'desktop',
            'gestion' => $_GET,
            'clientes' => $clientes,
            'obligaciones' => $obligaciones,
            'errors' => [],
        ], 'desktop/layouts/app');
    }

    /**
     * POST /expedientes/store — Persist new gestión
     */
    public function store(): void
    {
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        $result = $this->gestionService->registrar($_POST, $userId);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Gestión registrada exitosamente.'];
            $redirect = !empty($_POST['cliente_id'])
                ? route_url('/expedientes/ficha', 'desktop') . '&id=' . (int)$_POST['cliente_id']
                : route_url('/expedientes', 'desktop');
            header('Location: ' . $redirect);
            exit;
        }

        $clienteRepo = new \App\Repositories\ClienteRepository();
        $clientes = $clienteRepo->findAll(500, 0);
        $obligacionRepo = new \App\Repositories\ObligacionRepository();
        $obligaciones = $obligacionRepo->findAll(500, 0);

        $this->view('desktop/expedientes/create', [
            'title' => 'Registrar Gestión',
            'variant' => 'desktop',
            'gestion' => $_POST,
            'clientes' => $clientes,
            'obligaciones' => $obligaciones,
            'errors' => $result['errors'],
        ], 'desktop/layouts/app');
    }
}

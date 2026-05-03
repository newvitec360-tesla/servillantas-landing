<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Services\PagoService;

final class PagosController extends Controller
{
    private PagoService $service;

    public function __construct()
    {
        $this->service = new PagoService();
    }

    /**
     * GET /pagos — List with KPIs, donut, table
     */
    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $estado = trim($_GET['estado'] ?? '');
        $medio = trim($_GET['medio'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));

        $result = $this->service->listar($query, $estado, $medio, $page);
        $kpis = $this->service->kpis();

        $this->view('desktop/pagos/index', [
            'title' => 'Pagos y Recaudo',
            'variant' => 'desktop',
            'pagos' => $result['data'],
            'pagination' => $result,
            'kpis' => $kpis,
            'filters' => ['q' => $query, 'estado' => $estado, 'medio' => $medio],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /pagos/show&id=X — Payment detail
     */
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/pagos', 'desktop'));
            exit;
        }

        $pago = $this->service->obtener($id);
        if (!$pago) {
            http_response_code(404);
            $this->view('errors/404', ['path' => '/pagos/show']);
            return;
        }

        $this->view('desktop/pagos/show', [
            'title' => 'Pago #' . $pago['id'],
            'variant' => 'desktop',
            'pago' => $pago,
        ], 'desktop/layouts/app');
    }

    /**
     * GET /pagos/create — Register payment form
     */
    public function create(): void
    {
        $clienteRepo = new \App\Repositories\ClienteRepository();
        $clientes = $clienteRepo->findAll(500, 0);

        $obligacionRepo = new \App\Repositories\ObligacionRepository();
        $obligaciones = $obligacionRepo->findAll(500, 0);

        $this->view('desktop/pagos/create', [
            'title' => 'Registrar Pago',
            'variant' => 'desktop',
            'pago' => [],
            'clientes' => $clientes,
            'obligaciones' => $obligaciones,
            'errors' => [],
        ], 'desktop/layouts/app');
    }

    /**
     * POST /pagos/store — Persist new payment
     */
    public function store(): void
    {
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        $result = $this->service->registrar($_POST, $userId);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pago registrado exitosamente.'];
            header('Location: ' . route_url('/pagos', 'desktop'));
            exit;
        }

        $clienteRepo = new \App\Repositories\ClienteRepository();
        $clientes = $clienteRepo->findAll(500, 0);
        $obligacionRepo = new \App\Repositories\ObligacionRepository();
        $obligaciones = $obligacionRepo->findAll(500, 0);

        $this->view('desktop/pagos/create', [
            'title' => 'Registrar Pago',
            'variant' => 'desktop',
            'pago' => $_POST,
            'clientes' => $clientes,
            'obligaciones' => $obligaciones,
            'errors' => $result['errors'],
        ], 'desktop/layouts/app');
    }

    /**
     * POST /pagos/validate — Approve a payment
     */
    public function validatePago(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $result = $this->service->validarPago($id);

        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Pago validado.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];

        header('Location: ' . route_url('/pagos/show', 'desktop') . '&id=' . $id);
        exit;
    }

    /**
     * POST /pagos/reject — Reject a payment
     */
    public function rejectPago(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $result = $this->service->rechazarPago($id);

        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Pago rechazado.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];

        header('Location: ' . route_url('/pagos/show', 'desktop') . '&id=' . $id);
        exit;
    }
}

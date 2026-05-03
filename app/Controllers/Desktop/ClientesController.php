<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Services\ClienteService;

final class ClientesController extends Controller
{
    private ClienteService $service;

    public function __construct()
    {
        $this->service = new ClienteService();
    }

    /**
     * GET /clientes — List with search, filters, pagination
     */
    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $estado = trim($_GET['estado'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));

        $result = $this->service->listar($query, $estado, $page);
        $kpis = $this->service->kpis();

        $this->view('desktop/clientes/index', [
            'title' => 'Clientes',
            'variant' => 'desktop',
            'clientes' => $result['data'],
            'pagination' => $result,
            'kpis' => $kpis,
            'filters' => ['q' => $query, 'estado' => $estado],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /clientes/show&id=X — Client detail with relations
     */
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/clientes', 'desktop'));
            exit;
        }

        $cliente = $this->service->obtener($id);
        if (!$cliente) {
            http_response_code(404);
            $this->view('errors/404', ['path' => '/clientes/show']);
            return;
        }

        $this->view('desktop/clientes/show', [
            'title' => $cliente['nombre_completo'],
            'variant' => 'desktop',
            'cliente' => $cliente,
        ], 'desktop/layouts/app');
    }

    /**
     * GET /clientes/create — Show create form
     */
    public function create(): void
    {
        $this->view('desktop/clientes/create', [
            'title' => 'Nuevo Cliente',
            'variant' => 'desktop',
            'cliente' => [],
            'errors' => [],
        ], 'desktop/layouts/app');
    }

    /**
     * POST /clientes/store — Persist new client
     */
    public function store(): void
    {
        $result = $this->service->crear($_POST);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cliente creado exitosamente.'];
            header('Location: ' . route_url('/clientes', 'desktop'));
            exit;
        }

        $this->view('desktop/clientes/create', [
            'title' => 'Nuevo Cliente',
            'variant' => 'desktop',
            'cliente' => $_POST,
            'errors' => $result['errors'],
        ], 'desktop/layouts/app');
    }

    /**
     * GET /clientes/edit&id=X — Show edit form
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $cliente = $this->service->obtener($id);

        if (!$cliente) {
            header('Location: ' . route_url('/clientes', 'desktop'));
            exit;
        }

        $this->view('desktop/clientes/create', [
            'title' => 'Editar: ' . $cliente['nombre_completo'],
            'variant' => 'desktop',
            'cliente' => $cliente,
            'errors' => [],
            'editing' => true,
        ], 'desktop/layouts/app');
    }

    /**
     * POST /clientes/update — Update existing client
     */
    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . route_url('/clientes', 'desktop'));
            exit;
        }

        $result = $this->service->actualizar($id, $_POST);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cliente actualizado.'];
            header('Location: ' . route_url('/clientes/show', 'desktop') . '&id=' . $id);
            exit;
        }

        $cliente = array_merge($_POST, ['id' => $id]);
        $this->view('desktop/clientes/create', [
            'title' => 'Editar Cliente',
            'variant' => 'desktop',
            'cliente' => $cliente,
            'errors' => $result['errors'],
            'editing' => true,
        ], 'desktop/layouts/app');
    }

    /**
     * POST /clientes/delete — Delete client
     */
    public function delete(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $result = $this->service->eliminar($id);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cliente eliminado.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        }

        header('Location: ' . route_url('/clientes', 'desktop'));
        exit;
    }
}

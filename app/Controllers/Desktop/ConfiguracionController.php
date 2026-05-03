<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Repositories\UsuarioRepository;
use App\Repositories\RolRepository;
use App\Repositories\ConfiguracionRepository;
use App\Services\PlantillaService;
use App\Services\UsuarioService;
use App\Services\RolService;
use App\Services\ConfiguracionService;

final class ConfiguracionController extends Controller
{
    /**
     * GET /configuracion — Settings with 7 tabs
     */
    public function index(): void
    {
        $userRepo = new UsuarioRepository();
        $rolRepo = new RolRepository();
        $configService = new ConfiguracionService();
        $plantillaService = new PlantillaService();

        $usuarios = $userRepo->listWithRoles();
        $roles = $rolRepo->findAll();
        $allPermisos = $rolRepo->findAllPermisos();
        $dbMatrix = $rolRepo->getFullMatrix();
        $byEstado = $userRepo->countByEstado();
        $byRol = $userRepo->countByRol();
        $plantillas = $plantillaService->listar('', '', 'activa');

        // General config from DB
        $generalConfig = $configService->getGeneral();
        $politicas = $configService->listPoliticas();

        // Permissions from config
        $permConfig = require __DIR__ . '/../../../config/permissions.php';

        // Modules for matrix display
        $modules = ['Dashboard', 'Clientes', 'Cartera', 'Pagos', 'Expedientes', 'Campañas', 'Reportes', 'Configuración'];

        // Flash messages
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->view('desktop/configuracion/index', [
            'title' => 'Configuración del sistema',
            'variant' => 'desktop',
            'usuarios' => $usuarios,
            'roles' => $roles,
            'allPermisos' => $allPermisos,
            'dbMatrix' => $dbMatrix,
            'permConfig' => $permConfig,
            'byEstado' => $byEstado,
            'byRol' => $byRol,
            'plantillas' => $plantillas,
            'modules' => $modules,
            'flash' => $flash,
            'generalConfig' => $generalConfig,
            'politicas' => $politicas,
        ], 'desktop/layouts/app');
    }

    // ──────────────────────────────────────────────
    //  CRUD USUARIOS
    // ──────────────────────────────────────────────

    /**
     * POST /configuracion/usuarios/store — Create new user
     */
    public function storeUsuario(): void
    {
        $service = new UsuarioService();
        $result = $service->crear($_POST);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Usuario creado exitosamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        }

        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    /**
     * POST /configuracion/usuarios/update — Update user
     */
    public function updateUsuario(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'ID de usuario inválido.'];
            header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
            exit;
        }

        $service = new UsuarioService();
        $result = $service->actualizar($id, $_POST);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Usuario actualizado.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        }

        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    /**
     * POST /configuracion/usuarios/toggle — Toggle user estado
     */
    public function toggleUsuario(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? '';

        $service = new UsuarioService();
        $result = $service->toggleEstado($id, $estado);

        if ($result['ok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Estado actualizado.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        }

        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    /**
     * POST /configuracion/usuarios/delete — Delete user
     */
    public function deleteUsuario(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        $service = new UsuarioService();
        $result = $service->eliminar($id);

        if ($result['ok']) {
            $msg = $result['warning'] ?? 'Usuario eliminado.';
            $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        }

        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    /**
     * POST /configuracion/usuarios/reset-password — Reset user password
     */
    public function resetPassword(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        $service = new UsuarioService();
        $result = $service->resetPassword($id);

        if ($result['ok']) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Contraseña reiniciada: ' . $result['new_password'] . ' — Cópiala ahora, no se mostrará de nuevo.'
            ];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        }

        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    // ──────────────────────────────────────────────
    //  CRUD ROLES + PERMISOS
    // ──────────────────────────────────────────────

    public function storeRol(): void
    {
        $service = new RolService();
        $result = $service->crear($_POST);
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Rol creado exitosamente.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    public function updateRol(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $service = new RolService();
        $result = $service->actualizar($id, $_POST);
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Rol actualizado.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    public function deleteRol(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $service = new RolService();
        $result = $service->eliminar($id);
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Rol eliminado.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    public function syncPermisos(): void
    {
        $rolId = (int)($_POST['rol_id'] ?? 0);
        $permisoIds = $_POST['permisos'] ?? [];
        $service = new RolService();
        $result = $service->syncPermisos($rolId, array_map('intval', $permisoIds));
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Permisos actualizados.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=usuarios');
        exit;
    }

    // ──────────────────────────────────────────────
    //  GENERAL + POLÍTICAS DE COBRO
    // ──────────────────────────────────────────────

    public function saveGeneral(): void
    {
        $service = new ConfiguracionService();
        $result = $service->saveGeneral($_POST);
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Configuración general guardada.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=general');
        exit;
    }

    public function storePolitica(): void
    {
        $service = new ConfiguracionService();
        $result = $service->crearPolitica($_POST);
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Política creada.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=politicas');
        exit;
    }

    public function updatePolitica(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $service = new ConfiguracionService();
        $result = $service->actualizarPolitica($id, $_POST);
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Política actualizada.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=politicas');
        exit;
    }

    public function deletePolitica(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $service = new ConfiguracionService();
        $result = $service->eliminarPolitica($id);
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Política eliminada.']
            : ['type' => 'error', 'message' => implode(' ', $result['errors'])];
        header('Location: ' . route_url('/configuracion', 'desktop') . '&tab=politicas');
        exit;
    }

}

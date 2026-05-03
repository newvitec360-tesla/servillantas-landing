<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;

final class AdminLandingController extends Controller
{
    public function index(): void
    {
        // Redirigir al gestor real (archivo estático protegido con PHP)
        header('Location: /landing/admin/');
        exit;
    }

    public function showLogin(): void
    {
        $this->view('desktop/admin/login', [
            'title' => 'Acceso Gestor Landing',
        ], null);
    }

    public function login(): void
    {
        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';

        $envUser = getenv('LANDING_ADMIN_USER') ?: 'admin_landing';
        $envPass = getenv('LANDING_ADMIN_PASS') ?: 'Servi2026!';

        if ($user === $envUser && $pass === $envPass) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['landing_admin_logged_in'] = true;
            header('Location: /landing/admin/');
            exit;
        }

        $this->view('desktop/admin/login', [
            'title' => 'Acceso Gestor Landing',
            'error' => 'Credenciales inválidas'
        ], null);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['landing_admin_logged_in']);
        header('Location: /index.php?r=/admin-landing/login');
        exit;
    }
}

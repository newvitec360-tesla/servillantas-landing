<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Repositories\UsuarioRepository;

final class AuthController extends Controller
{
    public function showLoginForm(): void
    {
        // Asegurar que exista token CSRF para el formulario
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $this->view('desktop/auth/login', ['title' => 'Login']);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($email) && !empty($password)) {
            $userRepo = new UsuarioRepository();
            $user = $userRepo->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['user_role_id'] = (int)$user['rol_id'];
                $_SESSION['user_name'] = $user['nombre'];
                
                $userRepo->updateUltimoLogin((int)$user['id']);
                
                $redirectUrl = $_SESSION['intended_url'] ?? '/index.php?r=/dashboard';
                unset($_SESSION['intended_url']);
                
                // Si la URL intentada era la oculta y el servidor no hizo rewrite al final:
                if (strpos($redirectUrl, 'admin-landing') !== false) {
                    $redirectUrl = '/index.php?r=/admin-landing';
                }
                
                header('Location: ' . $redirectUrl);
                exit;
            }
        }

        $this->view('desktop/auth/login', ['title' => 'Login', 'error' => 'Credenciales inválidas']);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header('Location: /index.php?r=/login');
        exit;
    }
}

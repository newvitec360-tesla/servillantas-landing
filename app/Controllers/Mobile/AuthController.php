<?php
declare(strict_types=1);

namespace App\Controllers\Mobile;

use App\Core\Controller;
use App\Repositories\UsuarioRepository;

final class AuthController extends Controller
{
    public function showLoginForm(): void
    {
        $this->view('desktop/auth/login', ['title' => 'Login']); // Reusing the same UI for now or create mobile version
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
                
                header('Location: /mobile/index.php?r=/dashboard');
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

        header('Location: /mobile/index.php?r=/login');
        exit;
    }
}

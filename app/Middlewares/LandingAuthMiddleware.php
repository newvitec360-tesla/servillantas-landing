<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Core\MiddlewareInterface;

final class LandingAuthMiddleware implements MiddlewareInterface
{
    public function handle(callable $next): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['landing_admin_logged_in']) || $_SESSION['landing_admin_logged_in'] !== true) {
            // Is it an API request?
            $route = $_GET['r'] ?? $_SERVER['REQUEST_URI'];
            if (strpos($route, '/api/') === 0 || strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            // Redirect to landing login
            header("Location: /index.php?r=/admin-landing/login");
            exit;
        }

        $next();
    }
}

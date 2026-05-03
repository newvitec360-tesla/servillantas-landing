<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Core\MiddlewareInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(callable $next): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/';
            header("Location: /index.php?r=/login");
            exit;
        }

        $next();
    }
}

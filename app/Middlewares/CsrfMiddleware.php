<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Core\MiddlewareInterface;

final class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(callable $next): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'], $token)) {
                http_response_code(403);
                die('Token CSRF inválido. La petición ha sido rechazada por seguridad.');
            }
        }

        $next();
    }
}

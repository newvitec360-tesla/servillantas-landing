<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Core\MiddlewareInterface;

final class SecurityHeadersMiddleware implements MiddlewareInterface
{
    public function handle(callable $next): void
    {
        if (!defined('CSP_NONCE')) {
            define('CSP_NONCE', bin2hex(random_bytes(16)));
        }

        header("Content-Security-Policy: default-src 'self'; script-src 'nonce-" . CSP_NONCE . "'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self';");
        header("X-Frame-Options: SAMEORIGIN");
        header("X-Content-Type-Options: nosniff");
        header("Referrer-Policy: strict-origin-when-cross-origin");

        $next();
    }
}

<?php
/**
 * Servillantas El Puente — Root Router
 * Detecta dispositivo y redirige a la landing correcta.
 * servillantaselpuente.com → /landing/desktop/desktop.php (o mobile)
 */

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isMobile = preg_match('/Mobile|Android|iPhone|iPod/i', $userAgent);

if ($isMobile) {
    header('Location: /landing/mobile/mobile.php', true, 302);
} else {
    header('Location: /landing/desktop/desktop.php', true, 302);
}
exit;

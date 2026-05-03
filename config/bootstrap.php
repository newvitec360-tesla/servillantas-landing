<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_name(getenv('SESSION_NAME') ?: 'servillantas_session');
    session_start();
}

function load_env(string $path): void
{
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"");
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Determinar ROOT_PATH dinámicamente para shared hosting y local
// En local: config/ está en servillantas_mvc/config/ → ROOT = servillantas_mvc/
// En shared hosting (flat): config/ está en public_html/config/ → ROOT = public_html/
define('ROOT_PATH', dirname(__DIR__));
load_env(ROOT_PATH . '/.env');

define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = APP_PATH . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

function config(string $key, mixed $default = null): mixed
{
    static $config = [];
    [$file, $item] = array_pad(explode('.', $key, 2), 2, null);

    if (!isset($config[$file])) {
        $path = ROOT_PATH . "/config/{$file}.php";
        $config[$file] = file_exists($path) ? require $path : [];
    }

    return $item ? ($config[$file][$item] ?? $default) : $config[$file];
}

function asset(string $path): string
{
    return '/public/assets/' . ltrim($path, '/');
}

function route_url(string $path = '/', string $variant = 'desktop'): string
{
    $path = $path === '/' ? '/' : '/' . trim($path, '/');
    return "/index.php?r=" . urlencode($path);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

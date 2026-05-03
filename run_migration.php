<?php
// One-time migration runner — delete after use
header('Content-Type: text/plain');

$env = [];
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with($line, '#')) continue;
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v, '"');
    }
}

try {
    $dsn = "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']};charset=utf8mb4";
    $pdo = new PDO($dsn, $env['DB_USERNAME'], $env['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connected OK\n\n";

    $sql = file_get_contents(__DIR__ . '/database/migrations/003_configuracion_tables.sql');
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $stmt) {
        if (empty($stmt)) continue;
        $pdo->exec($stmt);
        echo "OK: " . substr($stmt, 0, 60) . "...\n";
    }
    
    echo "\nAll migrations executed successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

<?php
// Script de migración para las tablas de Landing Page
header('Content-Type: text/plain');

$env = [];
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with($line, '#')) continue;
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v, '"');
    }
} else {
    die("No .env file found. This must run in the root directory.");
}

try {
    $dsn = "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']};charset=utf8mb4";
    $pdo = new PDO($dsn, $env['DB_USERNAME'], $env['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connected to DB successfully.\n\n";

    $sqlFile = __DIR__ . '/database/migrations/004_landing_tables.sql';
    if (!file_exists($sqlFile)) {
        die("Migration file 004_landing_tables.sql not found.");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $stmt) {
        if (empty($stmt)) continue;
        $pdo->exec($stmt);
        echo "OK: " . substr($stmt, 0, 60) . "...\n";
    }
    
    echo "\n[SUCCESS] Las tablas de Landing Pages han sido creadas con éxito en Producción.\n";
    echo "Por seguridad, por favor elimina este archivo (run_landing_migration.php) después de usarlo.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

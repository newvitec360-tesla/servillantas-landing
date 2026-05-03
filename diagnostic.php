<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "A\n";

require __DIR__ . '/config/bootstrap.php';

echo "B\n";

$_SESSION['user'] = ['id' => 1, 'nombre' => 'Admin', 'correo' => 'admin@test.com', 'rol_id' => 1];

echo "C\n";

// Test DashboardService directly
try {
    $svc = new \App\Services\DashboardService();
    echo "D - DashboardService created\n";
    $kpis = $svc->getKpis();
    echo "E - KPIs loaded\n";
} catch (\Throwable $e) {
    echo "D-FAIL: " . $e->getMessage() . "\n";
}

// Test View::render directly 
try {
    $controller = new \App\Controllers\Desktop\DashboardController();
    echo "F - DashboardController created\n";
} catch (\Throwable $e) {
    echo "F-FAIL: " . $e->getMessage() . "\n";
}

echo "G - DONE\n";

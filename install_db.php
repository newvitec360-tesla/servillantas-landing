<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/config/bootstrap.php';
use App\Core\Database;

try {
    $db = Database::connection();
    
    // Check if roles are already seeded
    $stmt = $db->query("SELECT count(*) FROM roles");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // 2. Demo Seed (without the admin user)
        $seed = file_get_contents(__DIR__ . '/database/seeds/seed_demo.sql');
        $db->exec($seed);
        echo "Demo data seeded successfully.<br>";
    } else {
        echo "Demo data already seeded.<br>";
    }
    
    // Check if admin user exists
    $stmt = $db->query("SELECT count(*) FROM usuarios WHERE correo = 'admin@servillantaselpuente.com'");
    if ($stmt->fetchColumn() == 0) {
        // 3. Insert Admin User with correct hash
        $hash = password_hash('Admin123!', PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, correo, telefono, password_hash, rol_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Administrador Demo', 'admin@servillantaselpuente.com', '3000000000', $hash, 1]);
        echo "Admin user created successfully.<br>";
    } else {
        echo "Admin user already exists.<br>";
    }
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
}

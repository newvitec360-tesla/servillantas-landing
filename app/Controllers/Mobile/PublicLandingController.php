<?php
declare(strict_types=1);

namespace App\Controllers\Mobile;

use App\Core\Controller;

final class PublicLandingController extends Controller
{
    public function index(): void
    {
        // Auto-Migration de emergencia
        try {
            $db = \App\Core\Database::connection();
            $db->query("SELECT 1 FROM landing_pages LIMIT 1");
        } catch (\PDOException $e) {
            $sqlPath = __DIR__ . '/../../../../database/migrations/004_landing_tables.sql';
            if (file_exists($sqlPath)) {
                $sql = file_get_contents($sqlPath);
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                foreach ($statements as $stmt) {
                    if (!empty($stmt)) {
                        $db->exec($stmt);
                    }
                }
            }
        }

        $landingModel = new \App\Models\LandingPage();
        $page = $landingModel->getDefault();
        $content = $page['content_json'] ?? null;
        
        $data = [];
        if (!empty($content)) {
            $data = json_decode($content, true);
        }
        
        if (empty($data)) {
            $jsonPath = __DIR__ . '/../../../../public/assets/landing/data/landing-content.mock.json';
            if (file_exists($jsonPath)) {
                $data = json_decode(file_get_contents($jsonPath), true);
            }
        }

        $this->view('mobile/landing/index', ['data' => $data], null);
    }
}

<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Models\LandingPage;

class LandingApiController extends Controller
{
    private LandingPage $landingModel;

    public function __construct()
    {
        // Auto-Migration de emergencia para crear las tablas
        try {
            $db = \App\Core\Database::connection();
            $db->query("SELECT 1 FROM landing_pages LIMIT 1");
        } catch (\PDOException $e) {
            $sqlPath = __DIR__ . '/../../../database/migrations/004_landing_tables.sql';
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

        $this->landingModel = new LandingPage();
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function healthCheck()
    {
        try {
            $db = \App\Core\Database::connection();
            $db->query("SELECT 1");
            $this->jsonResponse([
                'success' => true,
                'api' => 'connected',
                'database' => 'connected',
                'timestamp' => date('c')
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'api' => 'connected',
                'database' => 'disconnected',
                'timestamp' => date('c'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Obtener el borrador actual
    public function getDraft()
    {
        $page = $this->landingModel->getDefault();
        if (!$page) {
            return $this->jsonResponse(['success' => false, 'message' => 'Not found'], 404);
        }

        // Return draft if exists, else return content, else empty
        $json = $page['draft_json'] ?? $page['content_json'] ?? '{}';
        
        $this->jsonResponse([
            'success' => true,
            'data' => json_decode($json, true)
        ]);
    }

    // Guardar borrador
    public function saveDraft()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!$data) {
            return $this->jsonResponse(['success' => false, 'message' => 'Invalid JSON'], 400);
        }

        try {
            $page = $this->landingModel->getDefault();
            
            if ($page) {
                $this->landingModel->update($page['id'], [
                    'draft_json' => json_encode($data, JSON_UNESCAPED_UNICODE)
                ]);
                $draftId = $page['id'];
            } else {
                $draftId = $this->landingModel->create([
                    'slug' => 'servillantas-el-puente',
                    'draft_json' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    'status' => 'draft'
                ]);
            }

            $this->jsonResponse([
                'success' => true, 
                'message' => 'Borrador guardado en base de datos correctamente.',
                'draftId' => $draftId,
                'updatedAt' => date('c')
            ]);
        } catch (\Exception $e) {
            error_log("[LANDING_DRAFT_ERROR] " . $e->getMessage());
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Error al guardar el borrador en la base de datos.'
            ], 500);
        }
    }

    // Publicar borrador a producción
    public function publish()
    {
        try {
            $page = $this->landingModel->getDefault();
            if (!$page) {
                return $this->jsonResponse(['success' => false, 'message' => 'No draft found'], 404);
            }

            $draft = $page['draft_json'];
            
            if ($draft) {
                $this->landingModel->update($page['id'], [
                    'content_json' => $draft,
                    'status' => 'published',
                    'published_at' => date('Y-m-d H:i:s')
                ]);
                
                // Here we could also save to landing_revisions
                $db = \App\Core\Database::connection();
                $db->prepare("INSERT INTO landing_revisions (landing_page_id, content_json, created_by) VALUES (?, ?, ?)")
                   ->execute([$page['id'], $draft, $_SESSION['user_id'] ?? null]);
            }

            $this->jsonResponse([
                'success' => true, 
                'message' => 'Cambios publicados correctamente en la landing.',
                'publishedAt' => date('c')
            ]);
        } catch (\Exception $e) {
            error_log("[LANDING_PUBLISH_ERROR] " . $e->getMessage());
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Error al publicar los cambios.'
            ], 500);
        }
    }
}

<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class LandingPage extends Model
{
    protected string $table = 'landing_pages';
    protected ?\PDO $db = null;

    public function __construct()
    {
        $this->db = \App\Core\Database::connection();
    }
    
    // We can define methods specific to this model, e.g. finding the default active landing
    public function getBySlug(string $slug): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ? LIMIT 1");
            if (!$stmt) return null;
            $stmt->execute([$slug]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (\PDOException $e) {
            // Si la tabla no existe o hay error de DB, devolvemos null silenciosamente 
            // para que el fallback del controlador actúe.
            return null;
        }
    }

    public function getDefault(): ?array
    {
        return $this->getBySlug('servillantas-el-puente');
    }

    public function update(int $id, array $data): bool
    {
        $sets = [];
        $params = [];
        foreach ($data as $key => $value) {
            $sets[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function create(array $data): bool
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));
        
        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_values($data));
    }
}

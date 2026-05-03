<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class RolRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function findAll(): array
    {
        return $this->db->query("SELECT * FROM roles ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function create(string $nombre, string $descripcion = ''): int
    {
        $stmt = $this->db->prepare("INSERT INTO roles (nombre, descripcion) VALUES (:nombre, :descripcion)");
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $nombre, string $descripcion = ''): void
    {
        $stmt = $this->db->prepare("UPDATE roles SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        // First delete role_permisos
        $stmt = $this->db->prepare("DELETE FROM roles_permisos WHERE rol_id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        // Then delete role
        $stmt = $this->db->prepare("DELETE FROM roles WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function countUsuariosByRol(int $rolId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE rol_id = :id");
        $stmt->bindValue(':id', $rolId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function nameExists(string $nombre, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM roles WHERE nombre = :nombre";
        if ($excludeId) {
            $sql .= " AND id != :eid";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        if ($excludeId) {
            $stmt->bindValue(':eid', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }

    // ── Permisos ──

    public function findAllPermisos(): array
    {
        return $this->db->query("SELECT * FROM permisos ORDER BY codigo ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermisosByRol(int $rolId): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.id, p.codigo, p.nombre FROM permisos p
             JOIN roles_permisos rp ON p.id = rp.permiso_id
             WHERE rp.rol_id = :rid ORDER BY p.codigo"
        );
        $stmt->bindValue(':rid', $rolId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sync permissions for a role: delete all, then insert selected
     */
    public function syncPermisos(int $rolId, array $permisoIds): void
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM roles_permisos WHERE rol_id = :rid");
            $stmt->bindValue(':rid', $rolId, PDO::PARAM_INT);
            $stmt->execute();

            if (!empty($permisoIds)) {
                $ins = $this->db->prepare("INSERT INTO roles_permisos (rol_id, permiso_id) VALUES (:rid, :pid)");
                foreach ($permisoIds as $pid) {
                    $ins->bindValue(':rid', $rolId, PDO::PARAM_INT);
                    $ins->bindValue(':pid', (int)$pid, PDO::PARAM_INT);
                    $ins->execute();
                }
            }
            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Get full permissions matrix: [rol_id => [permiso_id, ...]]
     */
    public function getFullMatrix(): array
    {
        $rows = $this->db->query(
            "SELECT rp.rol_id, rp.permiso_id FROM roles_permisos rp ORDER BY rp.rol_id"
        )->fetchAll(PDO::FETCH_ASSOC);

        $matrix = [];
        foreach ($rows as $r) {
            $matrix[(int)$r['rol_id']][] = (int)$r['permiso_id'];
        }
        return $matrix;
    }
}

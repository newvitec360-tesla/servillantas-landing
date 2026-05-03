<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class UsuarioRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'usuarios';
        $this->allowedColumns = ['nombre', 'correo', 'telefono', 'password_hash', 'rol_id', 'estado'];
        $this->tenantScoped = false;
    }

    /**
     * List users with role name
     */
    public function listWithRoles(): array
    {
        $sql = "SELECT u.*, r.nombre as rol_nombre
                FROM usuarios u
                LEFT JOIN roles r ON u.rol_id = r.id
                ORDER BY u.nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * List all roles
     */
    public function listRoles(): array
    {
        $sql = "SELECT * FROM roles ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * List all permissions
     */
    public function listPermisos(): array
    {
        $sql = "SELECT * FROM permisos ORDER BY codigo ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get role permissions matrix
     */
    public function getPermissionsMatrix(): array
    {
        $sql = "SELECT r.id as rol_id, r.nombre as rol_nombre, p.codigo as permiso_codigo
                FROM roles r
                LEFT JOIN roles_permisos rp ON r.id = rp.rol_id
                LEFT JOIN permisos p ON rp.permiso_id = p.id
                ORDER BY r.nombre, p.codigo";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $matrix = [];
        foreach ($rows as $row) {
            $rolName = $row['rol_nombre'];
            if (!isset($matrix[$rolName])) {
                $matrix[$rolName] = [];
            }
            if ($row['permiso_codigo']) {
                $matrix[$rolName][] = $row['permiso_codigo'];
            }
        }
        return $matrix;
    }

    /**
     * Count users by estado
     */
    public function countByEstado(): array
    {
        $sql = "SELECT estado, COUNT(*) as qty FROM usuarios GROUP BY estado";
        $stmt = $this->db->query($sql);
        $result = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$r['estado']] = (int)$r['qty'];
        }
        return $result;
    }

    /**
     * Count users by role
     */
    public function countByRol(): array
    {
        $sql = "SELECT r.nombre, COUNT(u.id) as qty
                FROM roles r
                LEFT JOIN usuarios u ON u.rol_id = r.id
                GROUP BY r.id, r.nombre
                ORDER BY qty DESC";
        $stmt = $this->db->query($sql);
        $result = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$r['nombre']] = (int)$r['qty'];
        }
        return $result;
    }
    /**
     * Find user by email (used by AuthController for login)
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT u.*, r.nombre as rol_nombre
                FROM usuarios u
                LEFT JOIN roles r ON u.rol_id = r.id
                WHERE u.correo = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Update last login timestamp
     */
    public function updateUltimoLogin(int $userId): void
    {
        $sql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
    /**
     * Update user estado
     */
    public function updateEstado(int $id, string $estado): void
    {
        $sql = "UPDATE usuarios SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Update password hash
     */
    public function updatePassword(int $id, string $hash): void
    {
        $sql = "UPDATE usuarios SET password_hash = :hash WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':hash', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

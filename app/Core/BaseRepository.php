<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

abstract class BaseRepository
{
    protected PDO $db;
    protected string $table;
    protected array $allowedColumns = [];
    protected bool $tenantScoped = false;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * Valida dinámicamente si una columna está permitida para evitar inyecciones.
     */
    protected function validateColumn(string $column): string
    {
        if (!in_array($column, $this->allowedColumns, true)) {
            throw new \InvalidArgumentException("Columna '$column' no permitida en '{$this->table}'.");
        }
        return $column;
    }

    /**
     * Recupera todos los registros aplicando scope si es necesario
     */
    public function findAll(int $limit = 100, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->tenantScoped) {
            // Ejemplo de scope, se adaptará según se necesite
            $sql .= " WHERE tenant_id = :tenant_id";
        }
        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        
        if ($this->tenantScoped) {
            $stmt->bindValue(':tenant_id', $_SESSION['tenant_id'] ?? 0, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Encuentra un registro por su PK
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        if ($this->tenantScoped) {
            $sql .= " AND tenant_id = :tenant_id";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if ($this->tenantScoped) {
            $stmt->bindValue(':tenant_id', $_SESSION['tenant_id'] ?? 0, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }

    /**
     * Inserta un registro utilizando prepared statements
     */
    public function insert(array $data): int
    {
        $columns = [];
        $placeholders = [];
        
        foreach ($data as $key => $value) {
            $columns[] = $this->validateColumn($key);
            $placeholders[] = ":$key";
        }

        if ($this->tenantScoped && !in_array('tenant_id', $columns)) {
            $columns[] = 'tenant_id';
            $placeholders[] = ':tenant_id';
            $data['tenant_id'] = $_SESSION['tenant_id'] ?? 0;
        }

        $colStr = implode(', ', $columns);
        $valStr = implode(', ', $placeholders);
        $sql = "INSERT INTO {$this->table} ($colStr) VALUES ($valStr)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return (int)$this->db->lastInsertId();
    }

    /**
     * Actualiza un registro usando prepared statements
     */
    public function update(int $id, array $data): bool
    {
        $sets = [];
        foreach ($data as $key => $value) {
            $col = $this->validateColumn($key);
            $sets[] = "$col = :$key";
        }

        $setStr = implode(', ', $sets);
        $sql = "UPDATE {$this->table} SET $setStr WHERE id = :id";
        
        if ($this->tenantScoped) {
            $sql .= " AND tenant_id = :tenant_id";
            $data['tenant_id'] = $_SESSION['tenant_id'] ?? 0;
        }
        
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Elimina un registro por ID
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        if ($this->tenantScoped) {
            $sql .= " AND tenant_id = :tenant_id";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if ($this->tenantScoped) {
            $stmt->bindValue(':tenant_id', $_SESSION['tenant_id'] ?? 0, PDO::PARAM_INT);
        }

        return $stmt->execute();
    }
}

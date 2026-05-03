<?php
declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    public function all(): array
    {
        $stmt = Database::connection()->query("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC LIMIT 100");
        return $stmt->fetchAll();
    }
}

<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class PlantillaRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'plantillas_mensajes';
        $this->allowedColumns = ['nombre', 'canal', 'asunto', 'contenido', 'nivel_riesgo_aplicable', 'estado'];
        $this->tenantScoped = false;
    }

    /**
     * Get all templates with optional filtering
     */
    public function search(string $query = '', string $canal = '', string $estado = ''): array
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(nombre LIKE :q OR contenido LIKE :q2)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
        }
        if ($canal !== '') {
            $conditions[] = "canal = :canal";
            $params[':canal'] = $canal;
        }
        if ($estado !== '') {
            $conditions[] = "estado = :estado";
            $params[':estado'] = $estado;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT * FROM plantillas_mensajes WHERE 1=1 {$where} ORDER BY nombre ASC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count by canal
     */
    public function countByCanal(): array
    {
        $sql = "SELECT canal, COUNT(*) as qty FROM plantillas_mensajes WHERE estado = 'activa' GROUP BY canal ORDER BY qty DESC";
        $stmt = $this->db->query($sql);
        $result = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$r['canal']] = (int)$r['qty'];
        }
        return $result;
    }
}

<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class ClienteRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'clientes';
        $this->allowedColumns = [
            'tipo_documento', 'numero_documento', 'nit', 'nombre_completo',
            'razon_social_referencia', 'placa_principal', 'referido_por',
            'estado_localizacion', 'observaciones', 'fallecido_flag', 'habeas_data_flag'
        ];
        $this->tenantScoped = false;
    }

    /**
     * Search clients with filters and pagination
     */
    public function search(string $query = '', string $estado = '', int $limit = 20, int $offset = 0): array
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(c.nombre_completo LIKE :q OR c.numero_documento LIKE :q2 OR c.nit LIKE :q3 OR c.placa_principal LIKE :q4)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
            $params[':q3'] = "%{$query}%";
            $params[':q4'] = "%{$query}%";
        }

        if ($estado !== '') {
            $conditions[] = "c.estado_localizacion = :estado";
            $params[':estado'] = $estado;
        }

        $where = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "
            SELECT c.*, 
                   COALESCE(SUM(o.saldo_actual), 0) AS saldo_total,
                   MAX(o.nivel_riesgo) AS max_riesgo,
                   MAX(o.antiguedad_dias) AS max_mora
            FROM clientes c
            LEFT JOIN obligaciones o ON o.cliente_id = c.id AND o.estado_obligacion != 'pagada'
            {$where}
            GROUP BY c.id
            ORDER BY saldo_total DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count clients matching filters (for pagination)
     */
    public function countFiltered(string $query = '', string $estado = ''): int
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(nombre_completo LIKE :q OR numero_documento LIKE :q2 OR nit LIKE :q3 OR placa_principal LIKE :q4)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
            $params[':q3'] = "%{$query}%";
            $params[':q4'] = "%{$query}%";
        }

        if ($estado !== '') {
            $conditions[] = "estado_localizacion = :estado";
            $params[':estado'] = $estado;
        }

        $where = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT COUNT(*) FROM {$this->table} {$where}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    /**
     * Get client with related data (phones, emails, obligations)
     */
    public function findWithRelations(int $id): ?array
    {
        $cliente = $this->findById($id);
        if (!$cliente) return null;

        // Phones
        $stmt = $this->db->prepare("SELECT * FROM clientes_telefonos WHERE cliente_id = :id ORDER BY es_principal DESC");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cliente['telefonos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Emails
        $stmt = $this->db->prepare("SELECT * FROM clientes_correos WHERE cliente_id = :id ORDER BY es_principal DESC");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cliente['correos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obligations
        $stmt = $this->db->prepare("SELECT * FROM obligaciones WHERE cliente_id = :id ORDER BY saldo_actual DESC");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cliente['obligaciones'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $cliente;
    }

    /**
     * Get KPIs for the clients module
     */
    public function getModuleKpis(): array
    {
        $total = (int)$this->db->query("SELECT COUNT(*) FROM clientes")->fetchColumn();

        $stmt = $this->db->query("SELECT COALESCE(SUM(saldo_actual), 0) FROM obligaciones WHERE estado_obligacion != 'pagada'");
        $saldoTotal = (float)$stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM clientes WHERE estado_localizacion IN ('inalcanzable','contacto_incompleto')");
        $sinContacto = (int)$stmt->fetchColumn();

        return [
            'total' => $total,
            'saldo_total' => $saldoTotal,
            'sin_contacto' => $sinContacto,
        ];
    }
}

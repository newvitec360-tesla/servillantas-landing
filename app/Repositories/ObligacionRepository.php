<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class ObligacionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'obligaciones';
        $this->allowedColumns = [
            'cliente_id', 'codigo_interno', 'tipo_obligacion', 'concepto',
            'origen_talonario', 'fecha_generacion', 'fecha_vencimiento',
            'valor_inicial', 'saldo_actual', 'estado_obligacion', 'nivel_riesgo',
            'fecha_ultimo_abono', 'valor_ultimo_abono', 'antiguedad_dias', 'observaciones'
        ];
        $this->tenantScoped = false;
    }

    /**
     * Search obligations with filters and pagination
     */
    public function search(string $query = '', string $riesgo = '', string $estado = '', int $limit = 20, int $offset = 0): array
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(c.nombre_completo LIKE :q OR o.codigo_interno LIKE :q2)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
        }

        if ($riesgo !== '') {
            $conditions[] = "o.nivel_riesgo = :riesgo";
            $params[':riesgo'] = $riesgo;
        }

        if ($estado !== '') {
            $conditions[] = "o.estado_obligacion = :estado";
            $params[':estado'] = $estado;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';

        $sql = "
            SELECT o.*, c.nombre_completo, c.numero_documento, c.tipo_documento
            FROM obligaciones o
            JOIN clientes c ON o.cliente_id = c.id
            WHERE 1=1 {$where}
            ORDER BY o.saldo_actual DESC, o.antiguedad_dias DESC
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
     * Count filtered results
     */
    public function countFiltered(string $query = '', string $riesgo = '', string $estado = ''): int
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(c.nombre_completo LIKE :q OR o.codigo_interno LIKE :q2)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
        }
        if ($riesgo !== '') {
            $conditions[] = "o.nivel_riesgo = :riesgo";
            $params[':riesgo'] = $riesgo;
        }
        if ($estado !== '') {
            $conditions[] = "o.estado_obligacion = :estado";
            $params[':estado'] = $estado;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT COUNT(*) FROM obligaciones o JOIN clientes c ON o.cliente_id = c.id WHERE 1=1 {$where}";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    /**
     * Get obligation with client data
     */
    public function findWithClient(int $id): ?array
    {
        $sql = "
            SELECT o.*, c.nombre_completo, c.numero_documento, c.tipo_documento, c.nit, c.placa_principal, c.estado_localizacion
            FROM obligaciones o
            JOIN clientes c ON o.cliente_id = c.id
            WHERE o.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Cartera KPIs
     */
    public function getKpis(): array
    {
        // Total cartera
        $stmt = $this->db->query("SELECT COALESCE(SUM(saldo_actual), 0) FROM obligaciones WHERE estado_obligacion != 'pagada'");
        $totalCartera = (float)$stmt->fetchColumn();

        // Saldo vencido
        $stmt = $this->db->query("SELECT COALESCE(SUM(saldo_actual), 0) FROM obligaciones WHERE estado_obligacion IN ('vencida','critica','juridico') AND estado_obligacion != 'pagada'");
        $saldoVencido = (float)$stmt->fetchColumn();

        // Promesas activas
        $stmt = $this->db->query("SELECT COUNT(*) FROM gestiones_cobranza WHERE compromiso_pago_fecha >= CURRENT_DATE()");
        $promesasActivas = (int)$stmt->fetchColumn();

        // Casos jurídicos
        $stmt = $this->db->query("SELECT COUNT(*) FROM obligaciones WHERE estado_obligacion = 'juridico'");
        $casosJuridicos = (int)$stmt->fetchColumn();

        // Recuperación del mes
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM pagos WHERE MONTH(fecha_pago) = MONTH(CURRENT_DATE()) AND YEAR(fecha_pago) = YEAR(CURRENT_DATE()) AND estado_validacion = 'validado'";
        $stmt = $this->db->query($sql);
        $recuperacionMes = (float)$stmt->fetchColumn();

        // Aging analysis
        $aging = [
            'al_dia' => 0,
            '1_30' => 0,
            '31_60' => 0,
            '61_90' => 0,
            'mas_90' => 0,
        ];

        $sql = "
            SELECT 
                COALESCE(SUM(CASE WHEN antiguedad_dias = 0 THEN saldo_actual ELSE 0 END), 0) as al_dia,
                COALESCE(SUM(CASE WHEN antiguedad_dias BETWEEN 1 AND 30 THEN saldo_actual ELSE 0 END), 0) as d1_30,
                COALESCE(SUM(CASE WHEN antiguedad_dias BETWEEN 31 AND 60 THEN saldo_actual ELSE 0 END), 0) as d31_60,
                COALESCE(SUM(CASE WHEN antiguedad_dias BETWEEN 61 AND 90 THEN saldo_actual ELSE 0 END), 0) as d61_90,
                COALESCE(SUM(CASE WHEN antiguedad_dias > 90 THEN saldo_actual ELSE 0 END), 0) as d90_plus
            FROM obligaciones 
            WHERE estado_obligacion != 'pagada'
        ";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $aging = [
                'al_dia' => (float)$row['al_dia'],
                '1_30' => (float)$row['d1_30'],
                '31_60' => (float)$row['d31_60'],
                '61_90' => (float)$row['d61_90'],
                'mas_90' => (float)$row['d90_plus'],
            ];
        }

        // Count by risk
        $riskCounts = ['S1' => 0, 'S2' => 0, 'S3' => 0];
        $stmt = $this->db->query("SELECT nivel_riesgo, COUNT(*) as cnt FROM obligaciones WHERE estado_obligacion != 'pagada' GROUP BY nivel_riesgo");
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $riskCounts[$r['nivel_riesgo']] = (int)$r['cnt'];
        }

        return [
            'total_cartera' => $totalCartera,
            'saldo_vencido' => $saldoVencido,
            'promesas_activas' => $promesasActivas,
            'casos_juridicos' => $casosJuridicos,
            'recuperacion_mes' => $recuperacionMes,
            'aging' => $aging,
            'risk_counts' => $riskCounts,
        ];
    }
}

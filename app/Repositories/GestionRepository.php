<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class GestionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'gestiones_cobranza';
        $this->allowedColumns = [
            'cliente_id', 'obligacion_id', 'usuario_id', 'fecha_gestion',
            'canal', 'resultado', 'observacion', 'compromiso_pago_fecha',
            'compromiso_pago_valor', 'proxima_gestion_fecha'
        ];
        $this->tenantScoped = false;
    }

    /**
     * Search gestiones with filters and pagination
     */
    public function search(string $query = '', string $canal = '', string $resultado = '', int $limit = 20, int $offset = 0): array
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(c.nombre_completo LIKE :q OR g.observacion LIKE :q2)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
        }
        if ($canal !== '') {
            $conditions[] = "g.canal = :canal";
            $params[':canal'] = $canal;
        }
        if ($resultado !== '') {
            $conditions[] = "g.resultado = :resultado";
            $params[':resultado'] = $resultado;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';

        $sql = "
            SELECT g.*, c.nombre_completo, c.numero_documento,
                   u.nombre AS gestor_nombre,
                   o.codigo_interno AS obligacion_codigo
            FROM gestiones_cobranza g
            JOIN clientes c ON g.cliente_id = c.id
            JOIN usuarios u ON g.usuario_id = u.id
            LEFT JOIN obligaciones o ON g.obligacion_id = o.id
            WHERE 1=1 {$where}
            ORDER BY g.fecha_gestion DESC
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
     * Count filtered
     */
    public function countFiltered(string $query = '', string $canal = '', string $resultado = ''): int
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(c.nombre_completo LIKE :q OR g.observacion LIKE :q2)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
        }
        if ($canal !== '') {
            $conditions[] = "g.canal = :canal";
            $params[':canal'] = $canal;
        }
        if ($resultado !== '') {
            $conditions[] = "g.resultado = :resultado";
            $params[':resultado'] = $resultado;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT COUNT(*) FROM gestiones_cobranza g JOIN clientes c ON g.cliente_id = c.id WHERE 1=1 {$where}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get gestiones for a specific client
     */
    public function findByCliente(int $clienteId): array
    {
        $sql = "
            SELECT g.*, u.nombre AS gestor_nombre, o.codigo_interno AS obligacion_codigo
            FROM gestiones_cobranza g
            JOIN usuarios u ON g.usuario_id = u.id
            LEFT JOIN obligaciones o ON g.obligacion_id = o.id
            WHERE g.cliente_id = :cid
            ORDER BY g.fecha_gestion DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cid', $clienteId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get gestiones KPIs
     */
    public function getKpis(): array
    {
        // Total gestiones del mes
        $sql = "SELECT COUNT(*) FROM gestiones_cobranza WHERE MONTH(fecha_gestion) = MONTH(CURRENT_DATE()) AND YEAR(fecha_gestion) = YEAR(CURRENT_DATE())";
        $totalMes = (int)$this->db->query($sql)->fetchColumn();

        // Gestiones hoy
        $sql = "SELECT COUNT(*) FROM gestiones_cobranza WHERE DATE(fecha_gestion) = CURRENT_DATE()";
        $hoy = (int)$this->db->query($sql)->fetchColumn();

        // Promesas activas (futuras)
        $sql = "SELECT COUNT(*) FROM gestiones_cobranza WHERE compromiso_pago_fecha >= CURRENT_DATE()";
        $promesas = (int)$this->db->query($sql)->fetchColumn();

        // Valor total promesas activas
        $sql = "SELECT COALESCE(SUM(compromiso_pago_valor), 0) FROM gestiones_cobranza WHERE compromiso_pago_fecha >= CURRENT_DATE()";
        $valorPromesas = (float)$this->db->query($sql)->fetchColumn();

        // Próximas gestiones pendientes
        $sql = "SELECT COUNT(*) FROM gestiones_cobranza WHERE proxima_gestion_fecha >= NOW() AND proxima_gestion_fecha <= DATE_ADD(NOW(), INTERVAL 7 DAY)";
        $proximasSemana = (int)$this->db->query($sql)->fetchColumn();

        // Por canal
        $canales = [];
        $sql = "SELECT canal, COUNT(*) AS qty FROM gestiones_cobranza WHERE MONTH(fecha_gestion) = MONTH(CURRENT_DATE()) GROUP BY canal ORDER BY qty DESC";
        $stmt = $this->db->query($sql);
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $canales[$r['canal']] = (int)$r['qty'];
        }

        // Por resultado
        $resultados = [];
        $sql = "SELECT resultado, COUNT(*) AS qty FROM gestiones_cobranza WHERE MONTH(fecha_gestion) = MONTH(CURRENT_DATE()) GROUP BY resultado ORDER BY qty DESC";
        $stmt = $this->db->query($sql);
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[$r['resultado']] = (int)$r['qty'];
        }

        return [
            'total_mes' => $totalMes,
            'hoy' => $hoy,
            'promesas' => $promesas,
            'valor_promesas' => $valorPromesas,
            'proximas_semana' => $proximasSemana,
            'canales' => $canales,
            'resultados' => $resultados,
        ];
    }
}

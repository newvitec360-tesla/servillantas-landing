<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class PagoRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'pagos';
        $this->allowedColumns = [
            'cliente_id', 'obligacion_id', 'fecha_pago', 'valor',
            'medio_pago', 'referencia_transaccion', 'comprobante_url',
            'estado_validacion', 'registrado_por'
        ];
        $this->tenantScoped = false;
    }

    /**
     * Search payments with filters and pagination
     */
    public function search(string $query = '', string $estado = '', string $medio = '', int $limit = 20, int $offset = 0): array
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(c.nombre_completo LIKE :q OR p.referencia_transaccion LIKE :q2)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
        }
        if ($estado !== '') {
            $conditions[] = "p.estado_validacion = :estado";
            $params[':estado'] = $estado;
        }
        if ($medio !== '') {
            $conditions[] = "p.medio_pago = :medio";
            $params[':medio'] = $medio;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';

        $sql = "
            SELECT p.*, c.nombre_completo, c.numero_documento,
                   o.codigo_interno AS obligacion_codigo,
                   u.nombre AS registrado_por_nombre
            FROM pagos p
            JOIN clientes c ON p.cliente_id = c.id
            LEFT JOIN obligaciones o ON p.obligacion_id = o.id
            LEFT JOIN usuarios u ON p.registrado_por = u.id
            WHERE 1=1 {$where}
            ORDER BY p.fecha_pago DESC
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
    public function countFiltered(string $query = '', string $estado = '', string $medio = ''): int
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(c.nombre_completo LIKE :q OR p.referencia_transaccion LIKE :q2)";
            $params[':q'] = "%{$query}%";
            $params[':q2'] = "%{$query}%";
        }
        if ($estado !== '') {
            $conditions[] = "p.estado_validacion = :estado";
            $params[':estado'] = $estado;
        }
        if ($medio !== '') {
            $conditions[] = "p.medio_pago = :medio";
            $params[':medio'] = $medio;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT COUNT(*) FROM pagos p JOIN clientes c ON p.cliente_id = c.id WHERE 1=1 {$where}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get single payment with relations
     */
    public function findWithRelations(int $id): ?array
    {
        $sql = "
            SELECT p.*, c.nombre_completo, c.numero_documento, c.tipo_documento,
                   o.codigo_interno AS obligacion_codigo, o.saldo_actual AS obligacion_saldo,
                   u.nombre AS registrado_por_nombre
            FROM pagos p
            JOIN clientes c ON p.cliente_id = c.id
            LEFT JOIN obligaciones o ON p.obligacion_id = o.id
            LEFT JOIN usuarios u ON p.registrado_por = u.id
            WHERE p.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Pagos KPIs
     */
    public function getKpis(): array
    {
        // Pagos recibidos hoy
        $sql = "SELECT COALESCE(SUM(valor), 0) AS monto, COUNT(*) AS qty FROM pagos WHERE DATE(fecha_pago) = CURRENT_DATE() AND estado_validacion = 'validado'";
        $row = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        $hoyMonto = (float)$row['monto'];
        $hoyCantidad = (int)$row['qty'];

        // Recaudo del mes
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM pagos WHERE MONTH(fecha_pago) = MONTH(CURRENT_DATE()) AND YEAR(fecha_pago) = YEAR(CURRENT_DATE()) AND estado_validacion = 'validado'";
        $recaudoMes = (float)$this->db->query($sql)->fetchColumn();

        // Pendientes por validar
        $sql = "SELECT COUNT(*) AS qty, COALESCE(SUM(valor), 0) AS monto FROM pagos WHERE estado_validacion = 'pendiente'";
        $pend = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);

        // By medio de pago
        $medios = [];
        $sql = "SELECT medio_pago, COALESCE(SUM(valor), 0) AS total FROM pagos WHERE estado_validacion = 'validado' AND MONTH(fecha_pago) = MONTH(CURRENT_DATE()) AND YEAR(fecha_pago) = YEAR(CURRENT_DATE()) GROUP BY medio_pago ORDER BY total DESC";
        $stmt = $this->db->query($sql);
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $medios[$r['medio_pago']] = (float)$r['total'];
        }

        // Rechazados
        $sql = "SELECT COUNT(*) FROM pagos WHERE estado_validacion = 'rechazado' AND MONTH(fecha_pago) = MONTH(CURRENT_DATE())";
        $rechazados = (int)$this->db->query($sql)->fetchColumn();

        return [
            'hoy_monto' => $hoyMonto,
            'hoy_cantidad' => $hoyCantidad,
            'recaudo_mes' => $recaudoMes,
            'pendientes_qty' => (int)$pend['qty'],
            'pendientes_monto' => (float)$pend['monto'],
            'rechazados' => $rechazados,
            'medios' => $medios,
        ];
    }

    /**
     * Update validation state
     */
    public function updateEstado(int $id, string $estado): bool
    {
        $valid = ['pendiente', 'validado', 'rechazado'];
        if (!in_array($estado, $valid, true)) {
            throw new \InvalidArgumentException("Estado '$estado' no válido.");
        }

        $stmt = $this->db->prepare("UPDATE pagos SET estado_validacion = :estado WHERE id = :id");
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

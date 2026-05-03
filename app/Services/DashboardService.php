<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

final class DashboardService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function getKpis(): array
    {
        try {
            // Cartera total
            $stmt = $this->db->query("SELECT SUM(saldo_actual) as total_cartera FROM obligaciones WHERE estado_obligacion != 'pagada'");
            $totalCartera = (float)($stmt->fetch()['total_cartera'] ?? 0);

            // Recaudo del mes
            $sql = "SELECT SUM(valor) as recaudo_mes FROM pagos WHERE MONTH(fecha_pago) = MONTH(CURRENT_DATE()) AND YEAR(fecha_pago) = YEAR(CURRENT_DATE()) AND estado_validacion = 'validado'";
            $stmt = $this->db->query($sql);
            $recaudoMes = (float)($stmt->fetch()['recaudo_mes'] ?? 0);

            // Casos S3
            $stmt = $this->db->query("SELECT COUNT(id) as casos_s3 FROM obligaciones WHERE nivel_riesgo = 'S3' AND estado_obligacion != 'pagada'");
            $casosS3 = (int)($stmt->fetch()['casos_s3'] ?? 0);

            // Clientes prioritarios (Top 5 by balance)
            $sql = "
                SELECT c.nombre_completo, o.saldo_actual, o.antiguedad_dias, o.nivel_riesgo
                FROM obligaciones o
                JOIN clientes c ON o.cliente_id = c.id
                WHERE o.estado_obligacion != 'pagada'
                ORDER BY o.saldo_actual DESC, o.antiguedad_dias DESC
                LIMIT 5
            ";
            $stmt = $this->db->query($sql);
            $clientesPrioritarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Total clientes
            $stmt = $this->db->query("SELECT COUNT(id) as total FROM clientes");
            $totalClientes = (int)($stmt->fetch()['total'] ?? 0);

            // Pagos pendientes de validar
            $stmt = $this->db->query("SELECT COUNT(id) as total FROM pagos WHERE estado_validacion = 'pendiente'");
            $pagosValidar = (int)($stmt->fetch()['total'] ?? 0);

            // Clientes sin contacto válido
            $sql = "SELECT COUNT(DISTINCT c.id) as total FROM clientes c 
                    WHERE c.estado_localizacion IN ('inalcanzable','contacto_incompleto')";
            $stmt = $this->db->query($sql);
            $sinContacto = (int)($stmt->fetch()['total'] ?? 0);

            // Promesas por vencer (próximas gestiones con compromiso)
            $sql = "SELECT COUNT(id) as total FROM gestiones_cobranza 
                    WHERE compromiso_pago_fecha IS NOT NULL 
                    AND compromiso_pago_fecha BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)";
            $stmt = $this->db->query($sql);
            $promesasVencer = (int)($stmt->fetch()['total'] ?? 0);

            return [
                'total_cartera' => $totalCartera,
                'recaudo_mes' => $recaudoMes,
                'casos_s3' => $casosS3,
                'clientes_prioritarios' => $clientesPrioritarios,
                'total_clientes' => $totalClientes,
                'pagos_validar' => $pagosValidar,
                'sin_contacto' => $sinContacto,
                'promesas_vencer' => $promesasVencer,
                'actividad_reciente' => [],
            ];
        } catch (\PDOException $e) {
            // Fallback when DB has no data yet
            return [
                'total_cartera' => 0,
                'recaudo_mes' => 0,
                'casos_s3' => 0,
                'clientes_prioritarios' => [],
                'total_clientes' => 0,
                'pagos_validar' => 0,
                'sin_contacto' => 0,
                'promesas_vencer' => 0,
                'actividad_reciente' => [],
            ];
        }
    }
}

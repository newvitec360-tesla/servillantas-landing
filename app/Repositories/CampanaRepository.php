<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class CampanaRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'campanas';
        $this->allowedColumns = [
            'nombre', 'canal', 'segmento_definicion', 'plantilla_id',
            'fecha_envio', 'enviado_por', 'estado'
        ];
        $this->tenantScoped = false;
    }

    /**
     * Search campaigns with filters and pagination
     */
    public function search(string $query = '', string $estado = '', string $canal = '', int $limit = 20, int $offset = 0): array
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "c.nombre LIKE :q";
            $params[':q'] = "%{$query}%";
        }
        if ($estado !== '') {
            $conditions[] = "c.estado = :estado";
            $params[':estado'] = $estado;
        }
        if ($canal !== '') {
            $conditions[] = "c.canal = :canal";
            $params[':canal'] = $canal;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';

        $sql = "
            SELECT c.*, u.nombre AS creador_nombre, p.nombre AS plantilla_nombre,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id) AS total_mensajes,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id AND estado_envio = 'enviado') AS enviados,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id AND estado_envio = 'abierto') AS abiertos,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id AND estado_envio = 'clic') AS clics
            FROM campanas c
            LEFT JOIN usuarios u ON c.enviado_por = u.id
            LEFT JOIN plantillas_mensajes p ON c.plantilla_id = p.id
            WHERE 1=1 {$where}
            ORDER BY c.created_at DESC
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
    public function countFiltered(string $query = '', string $estado = '', string $canal = ''): int
    {
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = "nombre LIKE :q";
            $params[':q'] = "%{$query}%";
        }
        if ($estado !== '') {
            $conditions[] = "estado = :estado";
            $params[':estado'] = $estado;
        }
        if ($canal !== '') {
            $conditions[] = "canal = :canal";
            $params[':canal'] = $canal;
        }

        $where = count($conditions) > 0 ? 'AND ' . implode(' AND ', $conditions) : '';
        $sql = "SELECT COUNT(*) FROM campanas WHERE 1=1 {$where}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get single campaign with stats
     */
    public function findWithStats(int $id): ?array
    {
        $sql = "
            SELECT c.*, u.nombre AS creador_nombre, p.nombre AS plantilla_nombre, p.contenido AS plantilla_contenido,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id) AS total_mensajes,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id AND estado_envio = 'enviado') AS enviados,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id AND estado_envio = 'entregado') AS entregados,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id AND estado_envio = 'abierto') AS abiertos,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id AND estado_envio = 'clic') AS clics,
                   (SELECT COUNT(*) FROM mensajes_enviados WHERE campana_id = c.id AND estado_envio = 'rebote') AS rebotes
            FROM campanas c
            LEFT JOIN usuarios u ON c.enviado_por = u.id
            LEFT JOIN plantillas_mensajes p ON c.plantilla_id = p.id
            WHERE c.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Get campaign messages
     */
    public function getMessages(int $campanaId, int $limit = 50): array
    {
        $sql = "
            SELECT m.*, cl.nombre_completo, cl.numero_documento
            FROM mensajes_enviados m
            JOIN clientes cl ON m.cliente_id = cl.id
            WHERE m.campana_id = :cid
            ORDER BY m.fecha_envio DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cid', $campanaId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Campaigns KPIs
     */
    public function getKpis(): array
    {
        // Active campaigns
        $sql = "SELECT COUNT(*) FROM campanas WHERE estado IN ('borrador','programada')";
        $activas = (int)$this->db->query($sql)->fetchColumn();

        // Sent campaigns
        $sql = "SELECT COUNT(*) FROM campanas WHERE estado = 'enviada'";
        $enviadas = (int)$this->db->query($sql)->fetchColumn();

        // Total messages sent this month
        $sql = "SELECT COUNT(*) FROM mensajes_enviados WHERE MONTH(fecha_envio) = MONTH(CURRENT_DATE()) AND YEAR(fecha_envio) = YEAR(CURRENT_DATE())";
        $mensajesMes = (int)$this->db->query($sql)->fetchColumn();

        // Open rate
        $sql = "SELECT COUNT(*) FROM mensajes_enviados WHERE estado_envio IN ('abierto','clic')";
        $abiertos = (int)$this->db->query($sql)->fetchColumn();
        $sql = "SELECT COUNT(*) FROM mensajes_enviados WHERE estado_envio != 'pendiente'";
        $totalEnviados = (int)$this->db->query($sql)->fetchColumn();
        $tasaApertura = $totalEnviados > 0 ? round($abiertos / $totalEnviados * 100, 1) : 0;

        // Click rate
        $sql = "SELECT COUNT(*) FROM mensajes_enviados WHERE estado_envio = 'clic'";
        $clics = (int)$this->db->query($sql)->fetchColumn();
        $tasaClic = $totalEnviados > 0 ? round($clics / $totalEnviados * 100, 1) : 0;

        // Plantillas
        $sql = "SELECT COUNT(*) FROM plantillas_mensajes WHERE estado = 'activa'";
        $plantillas = (int)$this->db->query($sql)->fetchColumn();

        return [
            'activas' => $activas,
            'enviadas' => $enviadas,
            'mensajes_mes' => $mensajesMes,
            'tasa_apertura' => $tasaApertura,
            'tasa_clic' => $tasaClic,
            'plantillas' => $plantillas,
        ];
    }

    /**
     * Get all active templates
     */
    public function getPlantillas(): array
    {
        $sql = "SELECT * FROM plantillas_mensajes WHERE estado = 'activa' ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class ConfiguracionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    // ── Configuración Sistema (key-value) ──

    public function get(string $clave): ?string
    {
        $stmt = $this->db->prepare("SELECT valor FROM configuracion_sistema WHERE clave = :c");
        $stmt->bindValue(':c', $clave, PDO::PARAM_STR);
        $stmt->execute();
        $r = $stmt->fetchColumn();
        return $r !== false ? (string)$r : null;
    }

    public function set(string $clave, string $valor, string $tipo = 'text', string $grupo = 'general'): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO configuracion_sistema (clave, valor, tipo, grupo) VALUES (:c, :v, :t, :g)
             ON DUPLICATE KEY UPDATE valor = :v2"
        );
        $stmt->bindValue(':c', $clave, PDO::PARAM_STR);
        $stmt->bindValue(':v', $valor, PDO::PARAM_STR);
        $stmt->bindValue(':t', $tipo, PDO::PARAM_STR);
        $stmt->bindValue(':g', $grupo, PDO::PARAM_STR);
        $stmt->bindValue(':v2', $valor, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getByGrupo(string $grupo): array
    {
        $stmt = $this->db->prepare("SELECT clave, valor FROM configuracion_sistema WHERE grupo = :g ORDER BY id");
        $stmt->bindValue(':g', $grupo, PDO::PARAM_STR);
        $stmt->execute();
        $result = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$r['clave']] = $r['valor'];
        }
        return $result;
    }

    // ── Políticas de Cobranza ──

    public function listPoliticas(): array
    {
        return $this->db->query("SELECT * FROM politicas_cobranza ORDER BY orden ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findPolitica(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM politicas_cobranza WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function createPolitica(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO politicas_cobranza (nombre, nivel_riesgo, dias_mora_desde, dias_mora_hasta, canales_permitidos, frecuencia_maxima, horario_inicio, horario_fin, activa, orden)
             VALUES (:n, :nr, :dd, :dh, :cp, :fm, :hi, :hf, :a, :o)"
        );
        $stmt->execute([
            ':n' => $data['nombre'],
            ':nr' => $data['nivel_riesgo'],
            ':dd' => (int)($data['dias_mora_desde'] ?? 0),
            ':dh' => !empty($data['dias_mora_hasta']) ? (int)$data['dias_mora_hasta'] : null,
            ':cp' => json_encode($data['canales'] ?? []),
            ':fm' => $data['frecuencia_maxima'] ?? '',
            ':hi' => $data['horario_inicio'] ?? '08:00:00',
            ':hf' => $data['horario_fin'] ?? '18:00:00',
            ':a' => isset($data['activa']) ? 1 : 1,
            ':o' => (int)($data['orden'] ?? 0),
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updatePolitica(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            "UPDATE politicas_cobranza SET nombre=:n, nivel_riesgo=:nr, dias_mora_desde=:dd, dias_mora_hasta=:dh,
             canales_permitidos=:cp, frecuencia_maxima=:fm, horario_inicio=:hi, horario_fin=:hf, orden=:o WHERE id=:id"
        );
        $stmt->execute([
            ':n' => $data['nombre'],
            ':nr' => $data['nivel_riesgo'],
            ':dd' => (int)($data['dias_mora_desde'] ?? 0),
            ':dh' => !empty($data['dias_mora_hasta']) ? (int)$data['dias_mora_hasta'] : null,
            ':cp' => json_encode($data['canales'] ?? []),
            ':fm' => $data['frecuencia_maxima'] ?? '',
            ':hi' => $data['horario_inicio'] ?? '08:00:00',
            ':hf' => $data['horario_fin'] ?? '18:00:00',
            ':o' => (int)($data['orden'] ?? 0),
            ':id' => $id,
        ]);
    }

    public function deletePolitica(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM politicas_cobranza WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

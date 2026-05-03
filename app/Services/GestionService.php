<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\GestionRepository;

final class GestionService
{
    private GestionRepository $repo;

    public function __construct()
    {
        $this->repo = new GestionRepository();
    }

    public function listar(string $query = '', string $canal = '', string $resultado = '', int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $data = $this->repo->search($query, $canal, $resultado, $perPage, $offset);
        $total = $this->repo->countFiltered($query, $canal, $resultado);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / max($perPage, 1)),
        ];
    }

    public function obtenerPorCliente(int $clienteId): array
    {
        return $this->repo->findByCliente($clienteId);
    }

    public function registrar(array $data, int $userId): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $gestionData = [
            'cliente_id' => (int)$data['cliente_id'],
            'obligacion_id' => !empty($data['obligacion_id']) ? (int)$data['obligacion_id'] : null,
            'usuario_id' => $userId,
            'fecha_gestion' => $data['fecha_gestion'] ?: date('Y-m-d H:i:s'),
            'canal' => trim($data['canal'] ?? ''),
            'resultado' => trim($data['resultado'] ?? ''),
            'observacion' => trim($data['observacion'] ?? ''),
            'compromiso_pago_fecha' => !empty($data['compromiso_pago_fecha']) ? $data['compromiso_pago_fecha'] : null,
            'compromiso_pago_valor' => !empty($data['compromiso_pago_valor']) ? (float)$data['compromiso_pago_valor'] : null,
            'proxima_gestion_fecha' => !empty($data['proxima_gestion_fecha']) ? $data['proxima_gestion_fecha'] : null,
        ];

        try {
            $id = $this->repo->insert($gestionData);
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al registrar gestión: ' . $e->getMessage()]];
        }
    }

    public function kpis(): array
    {
        return $this->repo->getKpis();
    }

    private function validar(array $data): array
    {
        $errors = [];
        if (empty($data['cliente_id']) || (int)$data['cliente_id'] <= 0) {
            $errors[] = 'El cliente es obligatorio.';
        }
        if (empty(trim($data['canal'] ?? ''))) {
            $errors[] = 'El canal de gestión es obligatorio.';
        }
        if (empty(trim($data['resultado'] ?? ''))) {
            $errors[] = 'El resultado de la gestión es obligatorio.';
        }
        return $errors;
    }
}

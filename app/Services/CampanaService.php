<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CampanaRepository;

final class CampanaService
{
    private CampanaRepository $repo;

    public function __construct()
    {
        $this->repo = new CampanaRepository();
    }

    public function listar(string $query = '', string $estado = '', string $canal = '', int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $data = $this->repo->search($query, $estado, $canal, $perPage, $offset);
        $total = $this->repo->countFiltered($query, $estado, $canal);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / max($perPage, 1)),
        ];
    }

    public function obtener(int $id): ?array
    {
        $campana = $this->repo->findWithStats($id);
        if ($campana) {
            $campana['mensajes'] = $this->repo->getMessages($id);
        }
        return $campana;
    }

    public function crear(array $data, int $userId): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $campData = [
            'nombre' => trim($data['nombre'] ?? ''),
            'canal' => trim($data['canal'] ?? ''),
            'segmento_definicion' => !empty($data['segmento']) ? json_encode($data['segmento']) : null,
            'plantilla_id' => !empty($data['plantilla_id']) ? (int)$data['plantilla_id'] : null,
            'fecha_envio' => !empty($data['fecha_envio']) ? $data['fecha_envio'] : null,
            'enviado_por' => $userId,
            'estado' => $data['estado'] ?? 'borrador',
        ];

        try {
            $id = $this->repo->insert($campData);
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al crear campaña: ' . $e->getMessage()]];
        }
    }

    public function actualizar(int $id, array $data): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $campData = [
            'nombre' => trim($data['nombre'] ?? ''),
            'canal' => trim($data['canal'] ?? ''),
            'segmento_definicion' => !empty($data['segmento']) ? json_encode($data['segmento']) : null,
            'plantilla_id' => !empty($data['plantilla_id']) ? (int)$data['plantilla_id'] : null,
            'fecha_envio' => !empty($data['fecha_envio']) ? $data['fecha_envio'] : null,
            'estado' => $data['estado'] ?? 'borrador',
        ];

        try {
            $this->repo->update($id, $campData);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al actualizar: ' . $e->getMessage()]];
        }
    }

    public function kpis(): array
    {
        return $this->repo->getKpis();
    }

    public function plantillas(): array
    {
        return $this->repo->getPlantillas();
    }

    private function validar(array $data): array
    {
        $errors = [];
        if (empty(trim($data['nombre'] ?? ''))) {
            $errors[] = 'El nombre de la campaña es obligatorio.';
        }
        if (empty(trim($data['canal'] ?? ''))) {
            $errors[] = 'El canal es obligatorio.';
        }
        $canalesValidos = ['WhatsApp', 'SMS', 'Correo', 'Llamada'];
        if (!empty($data['canal']) && !in_array($data['canal'], $canalesValidos, true)) {
            $errors[] = 'Canal no válido.';
        }
        $estadosValidos = ['borrador', 'programada', 'enviada', 'cancelada'];
        if (!empty($data['estado']) && !in_array($data['estado'], $estadosValidos, true)) {
            $errors[] = 'Estado no válido.';
        }
        return $errors;
    }
}

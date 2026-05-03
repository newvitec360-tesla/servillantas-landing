<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\PlantillaRepository;

final class PlantillaService
{
    private PlantillaRepository $repo;

    public function __construct()
    {
        $this->repo = new PlantillaRepository();
    }

    public function listar(string $query = '', string $canal = '', string $estado = ''): array
    {
        return $this->repo->search($query, $canal, $estado);
    }

    public function obtener(int $id): ?array
    {
        return $this->repo->findById($id);
    }

    public function crear(array $data): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $plantData = [
            'nombre' => trim($data['nombre'] ?? ''),
            'canal' => trim($data['canal'] ?? ''),
            'asunto' => trim($data['asunto'] ?? ''),
            'contenido' => trim($data['contenido'] ?? ''),
            'nivel_riesgo_aplicable' => !empty($data['nivel_riesgo_aplicable']) ? $data['nivel_riesgo_aplicable'] : null,
            'estado' => $data['estado'] ?? 'activa',
        ];

        try {
            $id = $this->repo->insert($plantData);
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al crear plantilla: ' . $e->getMessage()]];
        }
    }

    public function actualizar(int $id, array $data): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $plantData = [
            'nombre' => trim($data['nombre'] ?? ''),
            'canal' => trim($data['canal'] ?? ''),
            'asunto' => trim($data['asunto'] ?? ''),
            'contenido' => trim($data['contenido'] ?? ''),
            'nivel_riesgo_aplicable' => !empty($data['nivel_riesgo_aplicable']) ? $data['nivel_riesgo_aplicable'] : null,
            'estado' => $data['estado'] ?? 'activa',
        ];

        try {
            $this->repo->update($id, $plantData);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    public function countByCanal(): array
    {
        return $this->repo->countByCanal();
    }

    private function validar(array $data): array
    {
        $errors = [];
        if (empty(trim($data['nombre'] ?? ''))) {
            $errors[] = 'El nombre de la plantilla es obligatorio.';
        }
        if (empty(trim($data['canal'] ?? ''))) {
            $errors[] = 'El canal es obligatorio.';
        }
        if (empty(trim($data['contenido'] ?? ''))) {
            $errors[] = 'El contenido del mensaje es obligatorio.';
        }
        return $errors;
    }
}

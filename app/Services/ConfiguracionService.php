<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ConfiguracionRepository;

final class ConfiguracionService
{
    private ConfiguracionRepository $repo;

    public function __construct()
    {
        $this->repo = new ConfiguracionRepository();
    }

    public function saveGeneral(array $data): array
    {
        $fields = [
            'razon_social' => 'general', 'nit' => 'general',
            'direccion' => 'general', 'telefono' => 'general',
            'color_primario' => 'marca', 'color_secundario' => 'marca',
        ];
        try {
            foreach ($fields as $key => $grupo) {
                if (isset($data[$key])) {
                    $tipo = str_contains($key, 'color') ? 'color' : 'text';
                    $this->repo->set($key, trim($data[$key]), $tipo, $grupo);
                }
            }
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    public function getGeneral(): array
    {
        return array_merge($this->repo->getByGrupo('general'), $this->repo->getByGrupo('marca'));
    }

    public function listPoliticas(): array
    {
        return $this->repo->listPoliticas();
    }

    public function crearPolitica(array $data): array
    {
        if (empty(trim($data['nombre'] ?? ''))) {
            return ['ok' => false, 'errors' => ['El nombre es obligatorio.']];
        }
        $allowed = ['S1','S2','S3','juridico','preventivo'];
        if (!in_array($data['nivel_riesgo'] ?? '', $allowed, true)) {
            return ['ok' => false, 'errors' => ['Nivel de riesgo inválido.']];
        }
        try {
            $id = $this->repo->createPolitica($data);
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    public function actualizarPolitica(int $id, array $data): array
    {
        if (empty(trim($data['nombre'] ?? ''))) {
            return ['ok' => false, 'errors' => ['El nombre es obligatorio.']];
        }
        try {
            $this->repo->updatePolitica($id, $data);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    public function eliminarPolitica(int $id): array
    {
        try {
            $this->repo->deletePolitica($id);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }
}

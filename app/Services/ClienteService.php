<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ClienteRepository;

final class ClienteService
{
    private ClienteRepository $repo;

    public function __construct()
    {
        $this->repo = new ClienteRepository();
    }

    /**
     * List clients with search, filtering and pagination
     */
    public function listar(string $query = '', string $estado = '', int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $clientes = $this->repo->search($query, $estado, $perPage, $offset);
        $total = $this->repo->countFiltered($query, $estado);

        return [
            'data' => $clientes,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    /**
     * Get single client with all relations
     */
    public function obtener(int $id): ?array
    {
        return $this->repo->findWithRelations($id);
    }

    /**
     * Create a new client with validation
     */
    public function crear(array $data): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $clienteData = [
            'tipo_documento' => trim($data['tipo_documento'] ?? ''),
            'numero_documento' => trim($data['numero_documento'] ?? ''),
            'nit' => trim($data['nit'] ?? ''),
            'nombre_completo' => trim($data['nombre_completo'] ?? ''),
            'razon_social_referencia' => trim($data['razon_social_referencia'] ?? ''),
            'placa_principal' => strtoupper(trim($data['placa_principal'] ?? '')),
            'referido_por' => trim($data['referido_por'] ?? ''),
            'estado_localizacion' => $data['estado_localizacion'] ?? 'contactable',
            'observaciones' => trim($data['observaciones'] ?? ''),
            'habeas_data_flag' => isset($data['habeas_data_flag']) ? 1 : 0,
        ];

        try {
            $id = $this->repo->insert($clienteData);
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return ['ok' => false, 'errors' => ['Ya existe un cliente con ese documento.']];
            }
            throw $e;
        }
    }

    /**
     * Update existing client
     */
    public function actualizar(int $id, array $data): array
    {
        $errors = $this->validar($data, $id);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $clienteData = [
            'tipo_documento' => trim($data['tipo_documento'] ?? ''),
            'numero_documento' => trim($data['numero_documento'] ?? ''),
            'nit' => trim($data['nit'] ?? ''),
            'nombre_completo' => trim($data['nombre_completo'] ?? ''),
            'razon_social_referencia' => trim($data['razon_social_referencia'] ?? ''),
            'placa_principal' => strtoupper(trim($data['placa_principal'] ?? '')),
            'referido_por' => trim($data['referido_por'] ?? ''),
            'estado_localizacion' => $data['estado_localizacion'] ?? 'contactable',
            'observaciones' => trim($data['observaciones'] ?? ''),
            'habeas_data_flag' => isset($data['habeas_data_flag']) ? 1 : 0,
        ];

        try {
            $this->repo->update($id, $clienteData);
            return ['ok' => true];
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return ['ok' => false, 'errors' => ['Documento duplicado.']];
            }
            throw $e;
        }
    }

    /**
     * Delete client (only if no obligations)
     */
    public function eliminar(int $id): array
    {
        $cliente = $this->repo->findWithRelations($id);
        if (!$cliente) {
            return ['ok' => false, 'errors' => ['Cliente no encontrado.']];
        }

        if (!empty($cliente['obligaciones'])) {
            return ['ok' => false, 'errors' => ['No se puede eliminar un cliente con obligaciones activas.']];
        }

        $this->repo->delete($id);
        return ['ok' => true];
    }

    /**
     * Get module-level KPIs
     */
    public function kpis(): array
    {
        return $this->repo->getModuleKpis();
    }

    /**
     * Validate client data
     */
    private function validar(array $data, ?int $excludeId = null): array
    {
        $errors = [];

        if (empty(trim($data['nombre_completo'] ?? ''))) {
            $errors[] = 'El nombre completo es obligatorio.';
        }

        if (empty(trim($data['numero_documento'] ?? ''))) {
            $errors[] = 'El número de documento es obligatorio.';
        }

        $estadosValidos = ['contactable', 'contacto_incompleto', 'inalcanzable', 'visita_requerida'];
        if (!empty($data['estado_localizacion']) && !in_array($data['estado_localizacion'], $estadosValidos, true)) {
            $errors[] = 'Estado de localización no válido.';
        }

        return $errors;
    }
}

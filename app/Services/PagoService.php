<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\PagoRepository;

final class PagoService
{
    private PagoRepository $repo;

    public function __construct()
    {
        $this->repo = new PagoRepository();
    }

    /**
     * List payments with search, filters, pagination
     */
    public function listar(string $query = '', string $estado = '', string $medio = '', int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $data = $this->repo->search($query, $estado, $medio, $perPage, $offset);
        $total = $this->repo->countFiltered($query, $estado, $medio);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / max($perPage, 1)),
        ];
    }

    /**
     * Get single payment with relations
     */
    public function obtener(int $id): ?array
    {
        return $this->repo->findWithRelations($id);
    }

    /**
     * Register a new payment
     */
    public function registrar(array $data, int $userId): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $pagoData = [
            'cliente_id' => (int)$data['cliente_id'],
            'obligacion_id' => !empty($data['obligacion_id']) ? (int)$data['obligacion_id'] : null,
            'fecha_pago' => $data['fecha_pago'] ?: date('Y-m-d H:i:s'),
            'valor' => (float)$data['valor'],
            'medio_pago' => trim($data['medio_pago'] ?? ''),
            'referencia_transaccion' => trim($data['referencia_transaccion'] ?? ''),
            'estado_validacion' => $data['estado_validacion'] ?? 'pendiente',
            'registrado_por' => $userId,
        ];

        try {
            $id = $this->repo->insert($pagoData);
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al registrar pago: ' . $e->getMessage()]];
        }
    }

    /**
     * Validate/approve a payment
     */
    public function validarPago(int $id): array
    {
        try {
            $this->repo->updateEstado($id, 'validado');
            return ['ok' => true];
        } catch (\Exception $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()]];
        }
    }

    /**
     * Reject a payment
     */
    public function rechazarPago(int $id): array
    {
        try {
            $this->repo->updateEstado($id, 'rechazado');
            return ['ok' => true];
        } catch (\Exception $e) {
            return ['ok' => false, 'errors' => [$e->getMessage()]];
        }
    }

    /**
     * Get KPIs
     */
    public function kpis(): array
    {
        return $this->repo->getKpis();
    }

    /**
     * Validate payment data
     */
    private function validar(array $data): array
    {
        $errors = [];

        if (empty($data['cliente_id']) || (int)$data['cliente_id'] <= 0) {
            $errors[] = 'El cliente es obligatorio.';
        }
        if (empty($data['valor']) || (float)$data['valor'] <= 0) {
            $errors[] = 'El valor del pago debe ser mayor a cero.';
        }
        if (empty(trim($data['medio_pago'] ?? ''))) {
            $errors[] = 'El medio de pago es obligatorio.';
        }

        $estadosValidos = ['pendiente', 'validado', 'rechazado'];
        if (!empty($data['estado_validacion']) && !in_array($data['estado_validacion'], $estadosValidos, true)) {
            $errors[] = 'Estado de validación no válido.';
        }

        return $errors;
    }
}

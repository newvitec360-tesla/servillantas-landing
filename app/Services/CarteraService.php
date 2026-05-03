<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ObligacionRepository;

final class CarteraService
{
    private ObligacionRepository $repo;

    public function __construct()
    {
        $this->repo = new ObligacionRepository();
    }

    /**
     * List obligations with search, risk/state filters, pagination
     */
    public function listar(string $query = '', string $riesgo = '', string $estado = '', int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $data = $this->repo->search($query, $riesgo, $estado, $perPage, $offset);
        $total = $this->repo->countFiltered($query, $riesgo, $estado);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / max($perPage, 1)),
        ];
    }

    /**
     * Get single obligation with client data
     */
    public function obtener(int $id): ?array
    {
        return $this->repo->findWithClient($id);
    }

    /**
     * Create new obligation
     */
    public function crear(array $data): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $oblData = [
            'cliente_id' => (int)$data['cliente_id'],
            'codigo_interno' => trim($data['codigo_interno'] ?? ''),
            'tipo_obligacion' => trim($data['tipo_obligacion'] ?? ''),
            'concepto' => trim($data['concepto'] ?? ''),
            'origen_talonario' => trim($data['origen_talonario'] ?? ''),
            'fecha_generacion' => $data['fecha_generacion'] ?: null,
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?: null,
            'valor_inicial' => (float)($data['valor_inicial'] ?? 0),
            'saldo_actual' => (float)($data['saldo_actual'] ?? $data['valor_inicial'] ?? 0),
            'estado_obligacion' => $data['estado_obligacion'] ?? 'vigente',
            'nivel_riesgo' => $data['nivel_riesgo'] ?? 'S1',
            'antiguedad_dias' => (int)($data['antiguedad_dias'] ?? 0),
            'observaciones' => trim($data['observaciones'] ?? ''),
        ];

        try {
            $id = $this->repo->insert($oblData);
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al crear obligación: ' . $e->getMessage()]];
        }
    }

    /**
     * Update obligation
     */
    public function actualizar(int $id, array $data): array
    {
        $errors = $this->validar($data);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        $oblData = [
            'codigo_interno' => trim($data['codigo_interno'] ?? ''),
            'tipo_obligacion' => trim($data['tipo_obligacion'] ?? ''),
            'concepto' => trim($data['concepto'] ?? ''),
            'origen_talonario' => trim($data['origen_talonario'] ?? ''),
            'fecha_generacion' => $data['fecha_generacion'] ?: null,
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?: null,
            'valor_inicial' => (float)($data['valor_inicial'] ?? 0),
            'saldo_actual' => (float)($data['saldo_actual'] ?? 0),
            'estado_obligacion' => $data['estado_obligacion'] ?? 'vigente',
            'nivel_riesgo' => $data['nivel_riesgo'] ?? 'S1',
            'antiguedad_dias' => (int)($data['antiguedad_dias'] ?? 0),
            'observaciones' => trim($data['observaciones'] ?? ''),
        ];

        try {
            $this->repo->update($id, $oblData);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al actualizar: ' . $e->getMessage()]];
        }
    }

    /**
     * Get cartera KPIs
     */
    public function kpis(): array
    {
        return $this->repo->getKpis();
    }

    /**
     * Validate obligation data
     */
    private function validar(array $data): array
    {
        $errors = [];

        if (empty($data['cliente_id']) || (int)$data['cliente_id'] <= 0) {
            $errors[] = 'El cliente es obligatorio.';
        }
        if (empty(trim($data['codigo_interno'] ?? ''))) {
            $errors[] = 'El código interno es obligatorio.';
        }
        if (empty($data['valor_inicial']) || (float)$data['valor_inicial'] <= 0) {
            $errors[] = 'El valor inicial debe ser mayor a cero.';
        }

        $estadosValidos = ['vigente','vencida','critica','en_gestion','en_acuerdo','pagada','parcialmente_pagada','castigada','fallecido','juridico'];
        if (!empty($data['estado_obligacion']) && !in_array($data['estado_obligacion'], $estadosValidos, true)) {
            $errors[] = 'Estado de obligación no válido.';
        }

        $riesgosValidos = ['S1', 'S2', 'S3'];
        if (!empty($data['nivel_riesgo']) && !in_array($data['nivel_riesgo'], $riesgosValidos, true)) {
            $errors[] = 'Nivel de riesgo no válido.';
        }

        return $errors;
    }
}

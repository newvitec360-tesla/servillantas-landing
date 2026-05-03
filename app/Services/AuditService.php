<?php
declare(strict_types=1);

namespace App\Services;

final class AuditService
{
    public function register(string $entity, int|string $entityId, string $action, array $before = [], array $after = []): void
    {
        // Pendiente: insertar en tabla auditoria.
        // Toda modificación de saldo, pagos, riesgo, documentos y datos personales debe pasar por aquí.
    }
}

<?php
declare(strict_types=1);

namespace App\Services;

final class EtlImportService
{
    public function validateExcelStructure(array $headers): array
    {
        $required = ['documento', 'nombre', 'saldo_actual', 'fecha_ultimo_abono'];
        $missing = array_values(array_diff($required, $headers));

        return [
            'valid' => empty($missing),
            'missing_columns' => $missing,
            'warnings' => [],
        ];
    }
}

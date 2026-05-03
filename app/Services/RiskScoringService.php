<?php
declare(strict_types=1);

namespace App\Services;

final class RiskScoringService
{
    /**
     * Clasificación base. El equipo debe reemplazarla por reglas parametrizables.
     */
    public function classify(int $diasMora, bool $tieneSoporteJuridico, ?string $ultimoAbono): string
    {
        if ($diasMora >= 90) return 'S3';
        if ($diasMora >= 31 || $tieneSoporteJuridico) return 'S2';
        return 'S1';
    }
}

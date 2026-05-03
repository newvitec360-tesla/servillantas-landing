<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Repositories\ClienteRepository;
use App\Repositories\ObligacionRepository;
use App\Repositories\PagoRepository;
use App\Repositories\GestionRepository;
use App\Repositories\CampanaRepository;

final class ReportesController extends Controller
{
    /**
     * GET /reportes — Consolidated analytics dashboard
     */
    public function index(): void
    {
        $clienteRepo = new ClienteRepository();
        $obligacionRepo = new ObligacionRepository();
        $pagoRepo = new PagoRepository();
        $gestionRepo = new GestionRepository();
        $campanaRepo = new CampanaRepository();

        // Cartera KPIs
        $carteraKpis = $obligacionRepo->getKpis();
        // Pago KPIs
        $pagoKpis = $pagoRepo->getKpis();
        // Gestion KPIs
        $gestionKpis = $gestionRepo->getKpis();
        // Campaign KPIs
        $campanaKpis = $campanaRepo->getKpis();
        // Client KPIs
        $clienteKpis = $clienteRepo->getModuleKpis();

        // Recovery rate
        $carteraTotal = (float)($carteraKpis['saldo_total'] ?? 0);
        $recaudoMes = (float)($pagoKpis['recaudo_mes'] ?? 0);
        $tasaRecuperacion = $carteraTotal > 0 ? round($recaudoMes / $carteraTotal * 100, 2) : 0;

        // Conversion funnel (gestiones → pagos)
        $totalGestiones = (int)($gestionKpis['total_mes'] ?? 0);
        $promesas = (int)($gestionKpis['promesas'] ?? 0);
        $pagosValidados = (int)($pagoKpis['recaudo_hoy'] ?? 0); // using available metric

        $tasaContactoEfectivo = $totalGestiones > 0 ? round(($gestionKpis['hoy'] ?? 0) / max($totalGestiones, 1) * 100, 1) : 0;
        $tasaPromesa = $totalGestiones > 0 ? round($promesas / $totalGestiones * 100, 1) : 0;

        $this->view('desktop/reportes/index', [
            'title' => 'Reportes y Analítica',
            'variant' => 'desktop',
            'cartera' => $carteraKpis,
            'pagos' => $pagoKpis,
            'gestiones' => $gestionKpis,
            'campanas' => $campanaKpis,
            'clientes' => $clienteKpis,
            'tasaRecuperacion' => $tasaRecuperacion,
            'totalGestiones' => $totalGestiones,
            'promesas' => $promesas,
            'tasaContactoEfectivo' => $tasaContactoEfectivo,
            'tasaPromesa' => $tasaPromesa,
        ], 'desktop/layouts/app');
    }
}

<?php
declare(strict_types=1);

namespace App\Controllers\Desktop;

use App\Core\Controller;
use App\Services\DashboardService;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $service = new DashboardService();
        $kpis = $service->getKpis();

        $this->view('desktop/dashboard/index', [
            'title' => 'Dashboard',
            'variant' => 'desktop',
            'kpis' => $kpis
        ], 'desktop/layouts/app');
    }
}

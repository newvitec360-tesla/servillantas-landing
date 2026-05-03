<?php
declare(strict_types=1);

namespace App\Controllers\Mobile;

use App\Core\Controller;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->view('mobile/dashboard/index', [
            'title' => 'Inicio móvil',
            'variant' => 'mobile',
        ], 'mobile/layouts/app');
    }
}

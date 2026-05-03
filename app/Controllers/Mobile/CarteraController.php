<?php
declare(strict_types=1);

namespace App\Controllers\Mobile;

use App\Core\Controller;

final class CarteraController extends Controller
{
    public function index(): void
    {
        $this->view('mobile/cartera/index', [
            'title' => 'Cartera móvil',
            'variant' => 'mobile',
        ], 'mobile/layouts/app');
    }
}

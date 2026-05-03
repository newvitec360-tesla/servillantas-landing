<?php
declare(strict_types=1);

namespace App\Controllers\Mobile;

use App\Core\Controller;

final class ClientesController extends Controller
{
    public function index(): void
    {
        $this->view('mobile/clientes/index', [
            'title' => 'Clientes móvil',
            'variant' => 'mobile',
        ], 'mobile/layouts/app');
    }
}

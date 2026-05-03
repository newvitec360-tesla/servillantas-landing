<?php
declare(strict_types=1);

namespace App\Controllers\Mobile;

use App\Core\Controller;

final class DeudorController extends Controller
{
    public function index(): void
    {
        $this->view('mobile/deudor/index', [
            'title' => 'Portal deudor',
            'variant' => 'mobile',
        ], 'mobile/layouts/app');
    }
}

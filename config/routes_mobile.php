<?php

use App\Controllers\Mobile\PublicLandingController;

return [
    'GET' => [
        '/' => [PublicLandingController::class, 'index'],
    ],
    'POST' => [
    ]
];

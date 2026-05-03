<?php

use App\Controllers\Desktop\PublicLandingController;

return [
    'GET' => [
        '/' => [PublicLandingController::class, 'index'],
    ],
    'POST' => [
    ]
];

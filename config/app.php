<?php

return [
    'name' => getenv('APP_NAME') ?: 'Servillantas El Puente',
    'env' => getenv('APP_ENV') ?: 'local',
    'debug' => filter_var(getenv('APP_DEBUG') ?: true, FILTER_VALIDATE_BOOLEAN),
    'url' => getenv('APP_URL') ?: 'http://localhost:8000',
    'brand' => [
        'primary' => '#e10612',
        'primary_dark' => '#bf0610',
        'black' => '#0b0d12',
        'surface' => '#ffffff',
        'background' => '#f4f5f8',
    ],
];

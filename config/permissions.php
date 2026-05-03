<?php

return [
    'administrador_general' => ['*'],
    'analista_cartera' => [
        'clientes.ver', 'clientes.editar',
        'obligaciones.ver', 'gestiones.crear',
        'documentos.ver', 'campanas.ejecutar', 'pagos.ver'
    ],
    'coordinador_gerencia' => [
        'dashboard.ver', 'reportes.ver', 'clientes.ver', 'obligaciones.ver'
    ],
    'juridico' => [
        'clientes.ver', 'documentos.ver', 'juridico.ver', 'gestiones.crear'
    ],
];

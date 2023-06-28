<?php

declare(strict_types=1);

$dbConfig = require __DIR__ . '/credentials/db.php';

return [
    'paths' => [
        'migrations' => 'migrations'
    ],
    'migration_base_class' => \App\Infrastructure\Database\Migration::class,
    'environments' => [
        'default_migration_table' => 'migration',
        'default_environment' => 'mainline',
        'mainline' => [
            'adapter' => $dbConfig['driver'],
            'host' => $dbConfig['host'],
            'port' => $dbConfig['port'],
            'name' => $dbConfig['database'],
            'user' => $dbConfig['username'],
            'pass' => $dbConfig['password'],
        ],
    ]
];

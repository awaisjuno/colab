<?php
return [
    'default_connection' => 'default',

    'mysql' => [
        'default' => [
            'host' => 'localhost',
            'dbname' => 'colab',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'analytics' => [
            'host' => 'localhost',
            'dbname' => 'colab_analytics',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'logs' => [
            'host' => 'localhost',
            'dbname' => 'colab_logs',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
    ],

    'cache' => [
        'default' => 'file',
        'file' => [
            'path' => __DIR__ . '/../storage/cache/',
        ],
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'timeout' => 1.5,
            'database' => 0,
        ]
    ]
];

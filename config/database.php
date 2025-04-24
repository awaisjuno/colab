<?php

use System\Helpers\EnvLoader;

return [
    'default_connection' => 'default',

    'mysql' => [
        'default' => [
            'host' => EnvLoader::get('DB_HOST'),
            'dbname' => EnvLoader::get('DB_DATABASE'),
            'username' => EnvLoader::get('DB_USERNAME'),
            'password' => EnvLoader::get('DB_PASSWORD'),
            'charset' => EnvLoader::get('DB_CHARSET'),
        ],
        'analytics' => [
            'host' => 'localhost',
            'dbname' => '',
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

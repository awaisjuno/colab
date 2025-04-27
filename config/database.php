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
            'max_connections' => EnvLoader::get('MAX_CONNECTIONS')
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
            'host' => EnvLoader::get('REDIS_HOST'),
            'port' => EnvLoader::get('REDIS_PORT'),
            'timeout' => EnvLoader::get('REDIS_TIMEOUT'),
            'database' => EnvLoader::get('REDIS_DATABASE'),
        ]
    ]
];

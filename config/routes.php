<?php

return [
    '/gg' => [
        'controller' => 'Pages',
        'method' => 'index',
        'middleware' => ['Authenticateff']
    ],
    'home' => [
        'controller' => 'Pages',
        'method' => 'show',
    ]
];
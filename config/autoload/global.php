<?php
return [
    'api-tools-content-negotiation' => [
        'selectors' => [],
    ],
    'db' => [
        'adapters' => [
            'DbAdapterApi' => [
                'database' => env('MYSQL_DATABASE'),
                'driver' => 'PDO_Mysql',
                'hostname' => env('MYSQL_HOSTNAME'),
                'username' => env('MYSQL_USER'),
                'password' => env('MYSQL_PASSWORD'),
                'port' => env('MYSQL_PORT'),
            ],
        ],
    ],
];

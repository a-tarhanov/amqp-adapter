<?php

return [

    'connection' => [
        'host' => env('AMQP_HOST', '127.0.0.1'),
        'port' => env('AMQP_PORT', '5672'),
        'user' => env('AMQP_USER', 'guest'),
        'password' => env('AMQP_PASSWORD', 'guest'),
    ],

    'default' => [
        'exchange' => env('AMQP_EXCHANGE', 'default'),
        'queue' => env('AMQP_QUEUE', 'default'),
    ],

    'options' => [
        'prefetch_count' => env('AMQP_PREFETCH_COUNT', 10),
    ],

];

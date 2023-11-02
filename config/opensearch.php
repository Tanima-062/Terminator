<?php

return [
    'hosts'  =>  [
        'host' => env('OPENSEARCH_HOST', 'https://localhost:9200'),
    ],
    'user'  =>  env('OPENSEARCH_USER', 'admin'),
    'password'  =>  env('OPENSEARCH_PASSWORD', 'admin'),
    'ssl_verification'  =>  env('OPENSEARCH_SSL_VERIFICATION', false),
];

<?php

use Inktweb\Bolcom\RetailerApi\Clients\V5\Client;

return [
    'client' => [
        'class' => Client::class,

        'default-account' => env('BOLCOM_RETAILER_API_DEFAULT_ACCOUNT', 'default'),

        'demo-mode' => env('BOLCOM_RETAILER_API_DEMO_MODE', true),
    ],

    'accounts' => [
        'default' => [
            'id' => env('BOLCOM_RETAILER_API_ID'),
            'secret' => env('BOLCOM_RETAILER_API_SECRET'),
        ],
    ],
];

<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Statum Consumer Key
    |--------------------------------------------------------------------------
    |
    | The Consumer Key retrieved from your user profile on the Statum portal.
    |
    */
    'consumer_key' => env('STATUM_CONSUMER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Statum Consumer Secret
    |--------------------------------------------------------------------------
    |
    | The Consumer Secret retrieved from your user profile on the Statum portal.
    |
    */
    'consumer_secret' => env('STATUM_CONSUMER_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Statum API Base URL
    |--------------------------------------------------------------------------
    |
    | The endpoint base URL for all outgoing requests. Defaults to production v2.
    |
    */
    'base_url' => env('STATUM_BASE_URL', 'https://api.statum.co.ke/api/v2'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Connection and request timeout settings in seconds.
    |
    */
    'timeout' => (float) env('STATUM_TIMEOUT', 30.0),
];

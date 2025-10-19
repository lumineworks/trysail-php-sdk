<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Trysail Base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for the Trysail Control Plane API.
    |
    */
    'base_url' => env('TRYSAIL_BASE_URL', 'http://localhost:8081'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for API requests.
    |
    */
    'timeout' => env('TRYSAIL_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | SSL Verification
    |--------------------------------------------------------------------------
    |
    | Whether to verify SSL certificates when making API requests.
    |
    */
    'verify_ssl' => env('TRYSAIL_VERIFY_SSL', true),

    /*
    |--------------------------------------------------------------------------
    | Additional Headers
    |--------------------------------------------------------------------------
    |
    | Additional headers to include in every API request.
    |
    */
    'headers' => [
        // 'X-Custom-Header' => 'value',
    ],
];
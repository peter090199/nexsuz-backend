<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | This determines what cross-origin operations may execute in web browsers.
    | Adjust these settings as needed.
    |
    */

    'paths' => ['api/*', 'storage/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // Allow all origins (frontend can access)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Allow all headers

    'exposed_headers' => ['Content-Disposition'], // Allow file downloads

    'max_age' => 0,

    'supports_credentials' => true, // Required if using authentication (e.g., tokens, cookies)

];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    | Your Logstream app API key. Get it from the Apps section of the dashboard.
    */
    'api_key' => env('LOGSTREAM_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    | Override only if you are self-hosting Logstream.
    */
    'base_url' => env('LOGSTREAM_BASE_URL', 'https://logger.borgstein.io'),

    /*
    |--------------------------------------------------------------------------
    | Async Logging
    |--------------------------------------------------------------------------
    | When true, logs are dispatched as queued jobs instead of synchronous
    | HTTP calls. Requires a configured queue driver.
    */
    'async' => env('LOGSTREAM_ASYNC', false),

    /*
    |--------------------------------------------------------------------------
    | Minimum Level
    |--------------------------------------------------------------------------
    | Minimum log level to send. One of: debug, info, warning, error.
    */
    'level' => env('LOGSTREAM_LEVEL', 'debug'),
];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Security
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'url_prefix' => env('ADMIN_URL_PREFIX', 'admin/hf-x7k9m-p2q8-2026-secure'),
        'max_login_attempts' => env('ADMIN_MAX_LOGIN_ATTEMPTS', 3),
        'lockout_minutes' => env('ADMIN_LOCKOUT_MINUTES', 30),
        'session_lifetime' => env('ADMIN_SESSION_LIFETIME', 15),
        'ip_whitelist' => env('ADMIN_IP_WHITELIST', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    */
    'password' => [
        'min_length' => 10,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_number' => true,
        'require_special' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limits' => [
        'login' => 5,
        'admin_login' => 5,
        'register' => 3,
        'api' => 60,
        'messages' => 10,
        'task_submit' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    */
    'session' => [
        'regenerate_on_login' => true,
        'invalidate_on_logout' => true,
        'encrypt' => true,
        'http_only' => true,
        'same_site' => 'lax',
    ],
];
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Callback URL
    |--------------------------------------------------------------------------
    |
    | This URL will be used as the default callback URL for payment gateways
    | if no specific callback URL is set for a gateway.
    |
    */
    'default_callback_url' => env('PAYMENT_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Auto Load Gateways
    |--------------------------------------------------------------------------
    |
    | Automatically load active gateways from database into payment config
    | on application boot. Disable this if you want to manually control
    | when gateways are loaded.
    |
    */
    'auto_load_gateways' => env('PAYMENT_AUTO_LOAD_GATEWAYS', true),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | Default currency for Iranian payment gateways
    | T = Toman, R = Rial
    |
    */
    'default_currency' => env('PAYMENT_DEFAULT_CURRENCY', 'T'),

    /*
    |--------------------------------------------------------------------------
    | Driver Default Configurations
    |--------------------------------------------------------------------------
    |
    | These are the default configurations for each driver.
    | These will be merged with user-defined configs from database.
    |
    */
    'driver_defaults' => [
        'local' => [
            'callbackUrl' => env('PAYMENT_CALLBACK_URL', '/callback'),
            'title' => 'Test Payment Gateway',
            'description' => 'This gateway is for testing only',
        ],

        'zarinpal' => [
            'mode' => 'normal', // normal, sandbox, zaringate
            'currency' => 'T',
        ],

        'saman' => [
            'currency' => 'T',
        ],

        'paypal' => [
            'mode' => 'normal', // normal, sandbox
            'currency' => 'USD',
        ],

        'stripe' => [
            'currency' => 'usd',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Enable Logging
    |--------------------------------------------------------------------------
    |
    | Log payment gateway requests and responses for debugging
    |
    */
    'enable_logging' => env('PAYMENT_ENABLE_LOGGING', false),

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | The database table name for storing payment gateways
    |
    */
    'table_name' => 'payment_gateways',
];

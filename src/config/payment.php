<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Default Provider
    |--------------------------------------------------------------------------
    |
    | Define the default provider, the providers allowed in the list below
    |
    */

    'default_provider' => env('PAY_PROVIDER', 'pagarme'),

    /*
    |--------------------------------------------------------------------------
    | Secret API Key
    |--------------------------------------------------------------------------
    |
    | Each gateway should provide a key to identify and authorize the
    | transactions through API
    |
    */

    'api_key' => env('PAY_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Providers config
    |--------------------------------------------------------------------------
    |
    | To configure anti fraud  definitions and taxes
    |
    */

    'providers' => [
        'pagarme' => [
            'percentage_tax' => env('PAY_PERCENTAGE_TAX',3.99), // Value in percentage
            'fixed_tax' => env('PAY_FIXED_TAX',0.39), // Value in BRL
            'anti_fraud' => true,
            'setup' => false
        ],
        'stripe' => [
            'percentage_tax' => env('PAY_PERCENTAGE_TAX', 3.6), // Value in percentage
            'fixed_tax' => env('PAY_FIXED_TAX', 3), // Value in MXN
            'anti_fraud' => true,
            'setup' => true
        ]
    ],

];

<?php


return [

    'default' => env('MDB_LOG_CHANNEL', 'stack'),

    'channels' => [
        'slack' => [
            'driver' => 'slack',
            'url' => env('MDB_LOG_SLACK_WEBHOOK_URL'),
            'level' => env('MDB_LOG_LEVEL_SLACK', 'critical'),
        ],
    ],

    'database' => [
        'model' => \App\Models\Logging::class
    ]

];

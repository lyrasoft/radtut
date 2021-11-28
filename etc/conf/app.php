<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2020 LYRASOFT.
 * @license    MIT
 */

declare(strict_types=1);

use Windwalker\Core\Provider\DateTimeProvider;

return [
    'secret' => '59ff8017903d4ac044d56a3f',

    'debug' => (bool) (env('APP_DEBUG') ?? false),

    'mode' => env('APP_ENV') ?? 'prod',

    'timezone' => 'UTC',

    'server_timezone' => 'UTC',

    'dump_server' => [
        'host' => env('DUMP_SERVER_HOST') ?: 'tcp://127.0.0.1:9912'
    ],

    'providers' => [
        DateTimeProvider::class
    ]
];

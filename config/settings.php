<?php

declare(strict_types=1);

use Monolog\Logger;

return static function (string $appEnv) {
    $settings = [
        'app_env' => $appEnv,
        'site_url' => 'https://сайт.рф/',
        'upload_directory' => __DIR__ . '/../public_html/uploads/',
        'di_compilation_path' => __DIR__ . '/../var/cache',
        'display_error_details' => false,
        'log_errors' => true,

        'logger' => [
            'name' => 'slim-app',
            'path' => 'php://stderr',
            'level' => Logger::DEBUG,
        ],

        'templates.dir' => __DIR__ . '/../template',
        'templates.cache' => __DIR__ . '/../var/cache/template',

        // db
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'dbname',
            'username' => 'dbuser',
            'password' => 'pass',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ],
    ];

    if ($appEnv === 'DEVELOPMENT') {
        // Overrides for development mode
        $settings['di_compilation_path'] = '';
        $settings['display_error_details'] = false;
        $settings['logger']['path'] = __DIR__ . '/../var/log/app.log';

        $settings['templates.cache'] = '';
    }

    return $settings;
};

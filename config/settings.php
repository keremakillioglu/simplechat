<?php
declare(strict_types=1);

use Monolog\Logger;

$container = $app->getContainer();

$container->set('settings', function () {
    return [
        'app' => [
            'name' => getenv('APP_NAME')
        ],
        'database' => [
            'driver'    => getenv('DB_DRIVER'),
            'database'  => getenv('DB_DATABASE'),
        ],
        'displayErrorDetails' => true,
        'logError'            => false,
        'logErrorDetails'     => false,
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => Logger::DEBUG,
        ],
    ];
});

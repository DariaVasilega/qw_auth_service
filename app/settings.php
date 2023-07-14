<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'locale' => [
                    'default' => 'en_US',
                    'allowed' => [
                        'en_US',
                        'uk_UA',
                    ],
                ],
                'auth' => [
                    'token' => [
                        'length' => 63,
                        'expiration' => '+4 hours',
                    ]
                ],
                'translationsPath' => __DIR__ . '/../resources/i18n',
                'displayErrorDetails' => true,
                'logError'            => true,
                'logErrorDetails'     => true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'db' => require __DIR__ . '/credentials/db.php',
                'encryption' => require __DIR__ . '/credentials/encryption.php',
                'api-query-builder' => [
                    'limit' => 15,
                    'orderBy' => [],
                    'excludedParameters' => [],
                ],
            ]);
        }
    ]);
};

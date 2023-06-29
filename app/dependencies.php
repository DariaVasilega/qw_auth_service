<?php

declare(strict_types=1);

use App\Application\Directory\LocaleInterface;
use App\Application\Settings\SettingsInterface;
use App\Infrastructure\Filesystem\Log\UserActionLogger;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory as ValidationFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Capsule::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $capsule = new Capsule();
            $capsule->addConnection($settings->get('db'));
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        },
        Translator::class => function (ContainerInterface $c) {
            /** @var LocaleInterface $locale */
            $locale = $c->get(LocaleInterface::class);
            $localeCode = $locale->getCurrentLocale();
            $settings = $c->get(SettingsInterface::class);
            $translationsPath = $settings->get('translationsPath');

            $loader = new FileLoader(new Filesystem(), $translationsPath);
            $loader->addNamespace('i18n', $translationsPath);
            $loader->load($localeCode, 'global', 'i18n');

            return new Translator($loader, $localeCode);
        },
        ValidationFactory::class => function (ContainerInterface $c) {
            $validationFactory = new ValidationFactory($c->get(Translator::class));

            /** @var Capsule $capsule */
            $capsule = $c->get(Capsule::class);
            $presenceVerifier = new DatabasePresenceVerifier($capsule->getDatabaseManager());

            $validationFactory->setPresenceVerifier($presenceVerifier);

            return $validationFactory;
        },
        UserActionLogger::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $logFile = isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/user_action.log';
            $handler = new StreamHandler($logFile, Logger::ERROR);

            $userActionsLogger = $logger->withName('user-action');
            $userActionsLogger->setHandlers([$handler]);

            return new UserActionLogger($userActionsLogger);
        },
    ]);
};

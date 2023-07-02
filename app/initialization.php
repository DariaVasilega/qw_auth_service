<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Illuminate\Contracts\Foundation\Application as IlluminateApplication;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Schema as IlluminateSchemaFacade;
use Illuminate\Support\Facades\Validator as IlluminateValidatorFacade;
use Illuminate\Validation\Factory as ValidationFactory;

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (false) {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache'); // Should be set to true in production
}

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../app/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Set up helpers
$helpers = require __DIR__ . '/../app/helpers.php';
$helpers($container);

// Boot Eloquent
/** @var Capsule $capsule */
$capsule = $container->get(Capsule::class);

// Configure Illuminate Facades
/** @var IlluminateApplication $laravelAppMock */
$laravelAppMock = ['db' => $capsule, 'db.schema' => $capsule::schema()];
IlluminateValidatorFacade::swap($container->get(ValidationFactory::class));
IlluminateSchemaFacade::setFacadeApplication($laravelAppMock);

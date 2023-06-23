<?php

declare(strict_types=1);

use App\Application\Directory\Locale;
use App\Application\Directory\LocaleInterface;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LocaleInterface::class => autowire(Locale::class),
    ]);
};

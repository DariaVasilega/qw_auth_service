<?php

declare(strict_types=1);

return function (\Slim\App $app) {
    $app->add(\App\Application\Middleware\ClientRestriction::class);
};

<?php

declare(strict_types=1);

return [
    \App\Console\LoadFixtures::class,
    \App\Console\Migrations\Create::class,
    \App\Console\Migrations\Migrate::class,
    \App\Console\Migrations\Rollback::class,
    \App\Console\Migrations\Status::class,
];

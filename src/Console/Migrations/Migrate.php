<?php

declare(strict_types=1);

namespace App\Console\Migrations;

final class Migrate extends \Phinx\Console\Command\Migrate
{
    use \App\Support\PhinxCommandWrapper;

    protected const NAME = 'migrate';
}

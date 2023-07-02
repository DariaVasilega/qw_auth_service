<?php

declare(strict_types=1);

namespace App\Console\Migrations;

final class Create extends \Phinx\Console\Command\Create
{
    use \App\Support\PhinxCommandWrapper;

    protected const NAME = 'create';
}

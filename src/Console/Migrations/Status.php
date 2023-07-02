<?php

declare(strict_types=1);

namespace App\Console\Migrations;

final class Status extends \Phinx\Console\Command\Status
{
    use \App\Support\PhinxCommandWrapper;

    protected const NAME = 'status';
}

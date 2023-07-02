<?php

declare(strict_types=1);

namespace App\Console\Migrations;

final class Rollback extends \Phinx\Console\Command\Rollback
{
    use \App\Support\PhinxCommandWrapper;

    protected const NAME = 'rollback';
}

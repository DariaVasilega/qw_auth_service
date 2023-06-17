<?php

declare(strict_types=1);

namespace App\Migration;

class Migration extends \Phinx\Migration\AbstractMigration {

    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public \Illuminate\Database\Capsule\Manager $capsule;

    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public \Illuminate\Database\Schema\Builder $schema;

    public function init(): void
    {
        $dbConfig = require __DIR__ . '/../../app/credentials/db.php';

        $this->capsule = new \Illuminate\Database\Capsule\Manager;
        $this->capsule->addConnection($dbConfig);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}
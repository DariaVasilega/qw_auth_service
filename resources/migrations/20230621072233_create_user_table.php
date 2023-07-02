<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateUserTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('user', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->enum('status', \App\Domain\User\Status::values())->default(\App\Domain\User\Status::ACTIVE->value);
            $table->string('password', 256);
        });
    }

    public function down(): void
    {
        $this->schema->drop('user');
    }
}

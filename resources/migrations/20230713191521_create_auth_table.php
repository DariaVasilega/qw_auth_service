<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateAuthTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('auth', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token', 63)->unique();
            $table->dateTime('expiration');

            $table
                ->foreign('email')
                ->references('email')
                ->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        $this->schema->drop('auth');
    }
}

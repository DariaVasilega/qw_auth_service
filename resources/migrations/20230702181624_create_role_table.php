<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateRoleTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('role', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('code', 63)->primary();
            $table->string('label', 255);
        });
    }

    public function down(): void
    {
        $this->schema->drop('role');
    }
}

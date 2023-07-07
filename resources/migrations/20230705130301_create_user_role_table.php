<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateUserRoleTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('user_role', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->string('role_code', 63);

            $table->primary(['user_id', 'role_code']);

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('role_code')
                ->references('code')
                ->on('role')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        $this->schema->drop('user_role');
    }
}

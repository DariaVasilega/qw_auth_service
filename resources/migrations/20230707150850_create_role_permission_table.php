<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateRolePermissionTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('role_permission', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('role_code', 63);
            $table->string('permission_code', 63);

            $table->primary(['role_code', 'permission_code']);

            $table
                ->foreign('role_code')
                ->references('code')
                ->on('role')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('permission_code')
                ->references('code')
                ->on('permission')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        $this->schema->drop('role_permission');
    }
}

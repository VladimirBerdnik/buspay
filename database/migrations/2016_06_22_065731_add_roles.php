<?php

use Saritasa\Roles\Enums\Roles;

/**
 * Adds admin role row.
 */
class AddRoles extends MigrationWithQueryBuilder
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->newQuery('roles')
            ->insert([
                'name' => 'Admin',
                'slug' => Roles::ADMIN,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $role = $this->newQuery('roles')->where(['slug' => Roles::ADMIN])->first(['id']);

        if ($role) {
            $this->newQuery('roles')->delete($role->id);
        }
    }
}

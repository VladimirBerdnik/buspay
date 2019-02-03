<?php

use Utils\CommentsTablesMigration;

class FillRolesTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $roles = [
            1 => 'admin',
            2 => 'support',
            3 => 'operator',
            4 => 'government',
        ];
        foreach ($roles as $id => $slug) {
            DB::table('roles')->insert(['id' => $id, 'slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table('roles')->delete();
    }
}

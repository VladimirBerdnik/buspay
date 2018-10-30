<?php

use Illuminate\Database\Migrations\Migration;

class FillRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
    public function down()
    {
        DB::table('roles')->delete();
    }
}

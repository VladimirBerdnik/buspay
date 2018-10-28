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
            'admin',
            'operator',
            'government',
        ];
        foreach ($roles as $slug) {
            DB::table('roles')->insert(['slug' => $slug]);
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

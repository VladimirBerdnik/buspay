<?php

use Illuminate\Database\Migrations\Migration;

class FillCardTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cardTypes = [
            1 => 'service',
            2 => 'driver',
            3 => 'default',
            4 => 'child',
            5 => 'retire',
            6 => 'free',
        ];

        foreach ($cardTypes as $id => $slug) {
            DB::table('card_types')->insert(['id' => $id, 'slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('card_types')->delete();
    }
}

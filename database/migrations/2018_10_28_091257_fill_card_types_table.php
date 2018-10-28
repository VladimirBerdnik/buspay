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
            'default',
            'driver',
            'child',
            'retire',
            'reduced',
            'free',
        ];

        foreach ($cardTypes as $slug) {
            DB::table('card_types')->insert(['slug' => $slug]);
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkDriversCardsWithTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers_cards', function (Blueprint $table) {
            $table->foreign(['card_id'])->on('cards')->references('id')->onDelete('RESTRICT');
            $table->foreign(['driver_id'])->on('drivers')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers_cards', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
            $table->dropForeign(['driver_id']);
        });
    }
}

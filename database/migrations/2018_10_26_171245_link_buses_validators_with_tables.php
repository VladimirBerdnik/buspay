<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinkBusesValidatorsWithTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses_validators', function (Blueprint $table) {
            $table->foreign(['bus_id'])->on('buses')->references('id')->onDelete('RESTRICT');
            $table->foreign(['validator_id'])->on('validators')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buses_validators', function (Blueprint $table) {
            $table->dropForeign(['bus_id']);
            $table->dropForeign(['validator_id']);
        });
    }
}

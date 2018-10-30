<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinkTariffFaresWithTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tariff_fares', function (Blueprint $table) {
            $table->foreign(['tariff_id'])->on('tariffs')->references('id')->onDelete('RESTRICT');
            $table->foreign(['tariff_period_id'])->on('tariff_periods')->references('id')->onDelete('RESTRICT');
            $table->foreign(['card_type_id'])->on('card_types')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tariff_fares', function (Blueprint $table) {
            $table->dropForeign(['tariff_id']);
            $table->dropForeign(['card_type_id']);
        });
    }
}

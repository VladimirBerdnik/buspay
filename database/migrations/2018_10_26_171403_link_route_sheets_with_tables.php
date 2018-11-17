<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinkRouteSheetsWithTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_sheets', function (Blueprint $table) {
            $table->foreign(['bus_id'])->on('buses')->references('id')->onDelete('RESTRICT');
            $table->foreign(['driver_id'])->on('drivers')->references('id')->onDelete('RESTRICT');
            $table->foreign(['route_id'])->on('routes')->references('id')->onDelete('RESTRICT');
            $table->foreign(['company_id'])->on('companies')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('route_sheets', function (Blueprint $table) {
            $table->dropForeign(['bus_id']);
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['route_id']);
            $table->dropForeign(['company_id']);
        });
    }
}

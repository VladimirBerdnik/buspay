<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinkDriversWithTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->foreign(['company_id'])->on('companies')->references('id')->onDelete('RESTRICT');
            $table->foreign(['bus_id'])->on('buses')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['bus_id']);
        });
    }
}

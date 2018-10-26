<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkBusesWithTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
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
        Schema::table('buses', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropForeign(['company_id']);
        });
    }
}

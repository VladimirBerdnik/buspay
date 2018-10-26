<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkCompaniesRoutesWithTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies_routes', function (Blueprint $table) {
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
        Schema::table('companies_routes', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropForeign(['company_id']);
        });
    }
}

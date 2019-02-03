<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class LinkBusesWithTables extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('buses', function (Blueprint $table): void {
            $table->foreign(['route_id'])->on('routes')->references('id')->onDelete('RESTRICT');
            $table->foreign(['company_id'])->on('companies')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table): void {
            $table->dropForeign(['route_id']);
            $table->dropForeign(['company_id']);
        });
    }
}

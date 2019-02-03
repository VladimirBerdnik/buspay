<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class LinkDriversWithTables extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table): void {
            $table->foreign(['company_id'])->on('companies')->references('id')->onDelete('RESTRICT');
            $table->foreign(['bus_id'])->on('buses')->references('id')->onDelete('RESTRICT');
            $table->foreign(['card_id'])->on('cards')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table): void {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['bus_id']);
            $table->dropForeign(['card_id']);
        });
    }
}

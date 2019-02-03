<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class LinkDriversCardsWithTables extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('drivers_cards', function (Blueprint $table): void {
            $table->foreign(['card_id'])->on('cards')->references('id')->onDelete('RESTRICT');
            $table->foreign(['driver_id'])->on('drivers')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('drivers_cards', function (Blueprint $table): void {
            $table->dropForeign(['card_id']);
            $table->dropForeign(['driver_id']);
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class LinkCardsWithTables extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table): void {
            $table->foreign(['card_type_id'])->on('card_types')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table): void {
            $table->dropForeign(['card_type_id']);
        });
    }
}

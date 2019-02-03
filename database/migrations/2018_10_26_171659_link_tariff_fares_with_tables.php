<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class LinkTariffFaresWithTables extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('tariff_fares', function (Blueprint $table): void {
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
    public function down(): void
    {
        Schema::table('tariff_fares', function (Blueprint $table): void {
            $table->dropForeign(['tariff_id']);
            $table->dropForeign(['card_type_id']);
        });
    }
}

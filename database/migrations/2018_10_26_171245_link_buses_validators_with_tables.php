<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class LinkBusesValidatorsWithTables extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('buses_validators', function (Blueprint $table): void {
            $table->foreign(['bus_id'])->on('buses')->references('id')->onDelete('RESTRICT');
            $table->foreign(['validator_id'])->on('validators')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('buses_validators', function (Blueprint $table): void {
            $table->dropForeign(['bus_id']);
            $table->dropForeign(['validator_id']);
        });
    }
}

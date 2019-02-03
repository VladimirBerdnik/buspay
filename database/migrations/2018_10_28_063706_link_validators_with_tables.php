<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class LinkValidatorsWithTables extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('validators', function (Blueprint $table): void {
            $table->foreign(['bus_id'])->on('buses')->references('id')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('validators', function (Blueprint $table): void {
            $table->dropForeign(['bus_id']);
        });
    }
}

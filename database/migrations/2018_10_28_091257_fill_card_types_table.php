<?php

use Utils\CommentsTablesMigration;

class FillCardTypesTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $cardTypes = [
            1 => 'service',
            2 => 'driver',
            3 => 'default',
            4 => 'child',
            5 => 'retire',
            6 => 'free',
            7 => 'qr',
        ];

        foreach ($cardTypes as $id => $slug) {
            DB::table('card_types')->insert(['id' => $id, 'slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table('card_types')->delete();
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddReplenishmentsTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('replenishments', function (Blueprint $table): void {
            $table->increments('id')->comment('Replenishment unique identifier');
            $table->unsignedInteger('card_id')->comment('Identifier of card that was replenished');
            $table->float('amount')->comment('Amount of card replenishment');
            $table->unsignedInteger('external_id')->comment('Identifier of replenishment in external storage');
            $table->timestamp('replenished_at')->comment('Date when card was replenished');

            $table->foreign(['card_id'])->references('id')->on('cards');
        });

        $this->commentTable('replenishments', 'Filling of card balance with some amount of money');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('replenishments', function (Blueprint $table): void {
            $table->dropForeign(['card_id']);
        });

        Schema::dropIfExists('replenishments');
    }
}

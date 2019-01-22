<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReplenishmentsTable extends Migration
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

            $table->timestamps();

            $table->foreign(['card_id'])->references('id')->on('cards');
        });
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

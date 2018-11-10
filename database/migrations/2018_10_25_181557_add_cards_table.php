<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id')->comment('Card unique identifier');
            $table->unsignedTinyInteger('card_type_id')->comment('Card type');
            $table->string('card_number', 10)->comment('Short card number, written on card case');
            $table->string('uin', 32)->comment('Unique card number, patched to ROM');
            $table->tinyInteger('active')->default(1)->comment('Does this card active or not');
            $table->timestamp('synchronized_at')
                ->nullable()
                ->comment('When this card was synchronized with external storage last time');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['card_number', 'deleted_at'], 'cards_main_unique');
            $table->unique(['uin', 'deleted_at'], 'cards_second_unique');
        });

        DB::statement("ALTER TABLE `cards` comment 'Authentication cards that can be recognized by validators'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}

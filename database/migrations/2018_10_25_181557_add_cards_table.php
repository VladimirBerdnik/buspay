<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedInteger('card_type_id')->comment('Card type');
            $table->string('card_number')->comment('Card authentication number');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['card_number', 'deleted_at'], 'cards_main_unique');
        });
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

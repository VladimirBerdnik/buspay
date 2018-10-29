<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_types', function (Blueprint $table) {
            $table->unsignedTinyInteger('id', true)->comment('Type unique identifier');
            $table->string('slug', 32)->comment('Type machine-readable text identifier');

            $table->softDeletes();

            $table->unique(['slug', 'deleted_at'], 'card_types_main_unique');
        });

        DB::statement("ALTER TABLE `card_types` comment 'Possible authenticated card types'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_types');
    }
}

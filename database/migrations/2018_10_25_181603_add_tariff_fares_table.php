<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTariffFaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariff_fares', function (Blueprint $table) {
            $table->increments('id')->comment('Tariff fare unique identifier');
            $table->unsignedInteger('tariff_id')->comment('Tariff identifier to which this fare belongs');
            $table->unsignedTinyInteger('card_type_id')->comment('Card type identifier to which this fare applicable');
            $table->unsignedInteger('amount')->comment('Road trip fare');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tariff_id', 'card_type_id', 'deleted_at'], 'tariff_fares_main_unique');
        });

        DB::statement("ALTER TABLE `tariff_fares` comment 'Amount of tariff fare for card type'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariff_fares');
    }
}

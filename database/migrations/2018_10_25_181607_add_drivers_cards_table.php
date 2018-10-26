<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDriversCardsTable extends Migration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers_cards', function (Blueprint $table) {
            $table->increments('id')->comment('Card to driver link unique identifier');
            $table->unsignedInteger('driver_id')->comment('Linked to card driver identifier');
            $table->unsignedInteger('card_id')->comment('Linked to driver card identifier');

            $this->activityPeriod($table, false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['driver_id', 'card_id', 'active_from', 'active_to'], 'drivers_cards_main_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers_cards');
    }
}

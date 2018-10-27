<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusesValidatorsTable extends Migration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses_validators', function (Blueprint $table) {
            $table->increments('id')->comment('Validator to bus link unique identifier');
            $table->unsignedInteger('bus_id')->comment('Linked to validator bus identifier');
            $table->unsignedInteger('validator_id')->comment('Linked to bus validator identifier');

            $this->activityPeriod($table, false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['bus_id', 'validator_id', 'active_from', 'active_to'], 'buses_validators_main_index');
        });

        DB::statement("ALTER TABLE `buses_validators` comment 'Historical bus to validator assignment information'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses_validators');
    }
}

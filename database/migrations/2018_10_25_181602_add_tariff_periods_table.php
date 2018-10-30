<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTariffPeriodsTable extends Migration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariff_periods', function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true)->comment('Tariff period unique identifier');

            $this->activityPeriod($table);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `tariff_periods` comment 'Tariffs activity periods'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariff_periods');
    }
}

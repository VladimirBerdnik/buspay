<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTariffsTable extends Migration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true)->comment('Tariff unique identifier');
            $table->string('name')->comment('Tariff name');

            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `tariffs` comment 'Payment tariffs information'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariffs');
    }
}

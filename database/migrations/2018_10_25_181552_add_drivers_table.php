<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id')->comment('Driver unique identifier');
            $table->unsignedInteger('company_id')->comment('Company identifier in which this driver works');
            $table->string('full_name')->comment('Driver full name');
            $table->unsignedInteger('bus_id')
                ->nullable()
                ->comment('Bus identifier, on which this driver usually works');
            $table->tinyInteger('active')->comment('Does this driver works or not, can be assigned to route or not');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['full_name', 'company_id', 'deleted_at'], 'drivers_main_unique');
        });

        DB::statement("ALTER TABLE `drivers` comment 'Driver that can drive buses. Works in transport companies'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validators', function (Blueprint $table) {
            $table->increments('id')->comment('Validator unique identifier');
            $table->string('serial_number', 32)->comment('Validator serial number');
            $table->string('model', 32)->comment('Validator model or manufacturer');
            $table->unsignedInteger('bus_id')->nullable()->comment('Current bus identifier where validator located');

            $table->timestamps();
            $table->softDeletes();

            $table->index('serial_number', 'validators_main_index');
            $table->unique(['serial_number', 'deleted_at'], 'validators_main_unique');
        });

        DB::statement("ALTER TABLE `validators` comment 'Smart devices that can authorize payment cards'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('validators');
    }
}

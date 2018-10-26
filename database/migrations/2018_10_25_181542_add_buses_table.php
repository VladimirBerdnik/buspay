<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->increments('id')->comment('Bus unique identifier');
            $table->unsignedInteger('company_id')->comment('Company identifier, to which this bus belongs');
            $table->string('model_name')->comment('Name of bus model');
            $table->string('state_number')->comment('Bus state number');
            $table->string('description')->nullable()->comment('Bus description or notes');
            $table->unsignedInteger('route_id')->nullable()->comment('Usual route identifier, on which this bus is');
            $table->tinyInteger('active')->comment('Does this bus works or not, can be assigned to route or not');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['state_number', 'deleted_at'], 'buses_main_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses');
    }
}

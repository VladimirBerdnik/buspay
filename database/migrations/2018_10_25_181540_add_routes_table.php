<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id')->comment('Route unique identifier');
            $table->string('name', 16)->comment('Route name AKA "bus number"');
            $table->unsignedInteger('company_id')
                ->nullable()
                ->comment('Currently assigned to route company identifier');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'deleted_at'], 'routes_main_unique');
        });

        DB::statement("ALTER TABLE `routes` comment 'Regular bus routes'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}

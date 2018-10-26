<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRouteSheetsTable extends Migration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_sheets', function (Blueprint $table) {
            $table->increments('id')->comment('Route sheet unique identifier');
            $table->unsignedInteger('route_id')
                ->nullable()
                ->comment('Bus route identifier, which served the driver on the bus');
            $table->unsignedInteger('bus_id')->comment('Bus identifier that is on route');
            $table->unsignedInteger('driver_id')->nullable()->comment('Driver identifier that is on bus on route');
            $table->tinyInteger('temporary')->comment('Is this route sheet temporary (reserve) or not');

            $this->activityPeriod($table, false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['route_id', 'bus_id', 'driver_id', 'active_from', 'active_to'], 'route_sheets_main_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_sheets');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompaniesRoutesTable extends Migration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_routes', function (Blueprint $table) {
            $table->increments('id')->comment('Route to company link unique identifier');
            $table->unsignedInteger('company_id')->comment('Linked to route company identifier');
            $table->unsignedInteger('route_id')->comment('Linked to company route identifier');

            $this->activityPeriod($table, false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'route_id', 'active_from', 'active_to'], 'companies_routes_main_index');
        });

        DB::statement("ALTER TABLE `companies_routes` comment 'Historical information about transport companies to routes assignments'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies_routes');
    }
}

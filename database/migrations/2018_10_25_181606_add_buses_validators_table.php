<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddBusesValidatorsTable extends CommentsTablesMigration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('buses_validators', function (Blueprint $table): void {
            $table->increments('id')->comment('Validator to bus link unique identifier');
            $table->unsignedInteger('bus_id')->comment('Linked to validator bus identifier');
            $table->unsignedInteger('validator_id')->comment('Linked to bus validator identifier');

            $this->activityPeriod($table, false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['bus_id', 'validator_id', 'active_from', 'active_to'], 'buses_validators_main_index');
        });

        $this->commentTable('buses_validators', 'Historical bus to validator assignment information');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('buses_validators');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddTariffPeriodsTable extends CommentsTablesMigration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tariff_periods', function (Blueprint $table): void {
            $table->unsignedSmallInteger('id', true)->comment('Tariff period unique identifier');

            $this->activityPeriod($table);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->commentTable('tariff_periods', 'Tariffs activity periods');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tariff_periods');
    }
}

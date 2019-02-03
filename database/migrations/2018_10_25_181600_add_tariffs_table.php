<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddTariffsTable extends CommentsTablesMigration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tariffs', function (Blueprint $table): void {
            $table->unsignedSmallInteger('id', true)->comment('Tariff unique identifier');
            $table->string('name')->comment('Tariff name');

            $table->timestamps();
            $table->softDeletes();
        });

        $this->commentTable('tariffs', 'Payment tariffs information');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddDriversCardsTable extends CommentsTablesMigration
{
    use ActivityPeriodMigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('drivers_cards', function (Blueprint $table): void {
            $table->increments('id')->comment('Card to driver link unique identifier');
            $table->unsignedInteger('driver_id')->comment('Linked to card driver identifier');
            $table->unsignedInteger('card_id')->comment('Linked to driver card identifier');

            $this->activityPeriod($table, false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['driver_id', 'card_id', 'active_from', 'active_to'], 'drivers_cards_main_index');
        });

        $this->commentTable('drivers_cards', 'Historical information about cards to drivers assignments');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers_cards');
    }
}

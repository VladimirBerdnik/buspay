<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddTariffFaresTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tariff_fares', function (Blueprint $table): void {
            $table->increments('id')->comment('Tariff fare unique identifier');
            $table->unsignedSmallInteger('tariff_period_id')->comment('Tariff period for which this fare valid');
            $table->unsignedSmallInteger('tariff_id')->comment('Tariff identifier to which this fare belongs');
            $table->unsignedTinyInteger('card_type_id')->comment('Card type identifier to which this fare applicable');
            $table->unsignedInteger('amount')->comment('Road trip fare');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tariff_period_id', 'tariff_id', 'card_type_id', 'deleted_at'], 'tariff_fares_main_unique');
        });

        $this->commentTable('tariff_fares', 'Amount of tariff fare for card type in tariff period');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tariff_fares');
    }
}

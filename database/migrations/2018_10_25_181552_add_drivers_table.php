<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddDriversTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table): void {
            $table->increments('id')->comment('Driver unique identifier');
            $table->unsignedInteger('company_id')->comment('Company identifier in which this driver works');
            $table->string('full_name', 96)->comment('Driver full name');
            $table->unsignedInteger('bus_id')
                ->nullable()
                ->comment('Bus identifier, on which this driver usually works');
            $table->unsignedInteger('card_id')
                ->nullable()
                ->comment('Current driver card identifier');
            $table->tinyInteger('active')
                ->nullable()
                ->comment('Does this driver works or not, can be assigned to route or not');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['full_name', 'company_id', 'deleted_at'], 'drivers_main_unique');
        });

        $this->commentTable('drivers', 'Driver that can drive buses. Works in transport companies');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
}

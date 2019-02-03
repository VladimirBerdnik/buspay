<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddValidatorsTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('validators', function (Blueprint $table): void {
            $table->increments('id')->comment('Validator unique identifier');
            $table->string('serial_number', 32)->comment('Validator serial number');
            $table->string('model', 32)->comment('Validator model or manufacturer');
            $table->unsignedInteger('external_id')->comment('External storage record identifier');
            $table->unsignedInteger('bus_id')->nullable()->comment('Current bus identifier where validator located');

            $table->timestamps();
            $table->softDeletes();

            $table->index('serial_number', 'validators_main_index');
            $table->unique(['serial_number', 'deleted_at'], 'validators_main_unique');
            $table->unique(['external_id', 'deleted_at'], 'validators_second_unique');
        });

        $this->commentTable('validators', 'Smart devices that can authorize payment cards');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('validators');
    }
}

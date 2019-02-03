<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class CreateLarametricsModelsTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('larametrics_models', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('model');
            $table->integer('model_id');
            $table->string('method');
            $table->text('original')->nullable();
            $table->text('changes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('larametrics_models');
    }
}

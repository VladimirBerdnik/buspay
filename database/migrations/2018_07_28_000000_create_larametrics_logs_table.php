<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class CreateLarametricsLogsTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('larametrics_logs', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('level');
            $table->text('message');
            $table->integer('user_id')->nullable();
            $table->string('email')->nullable();
            $table->text('trace');
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
        Schema::dropIfExists('larametrics_logs');
    }
}

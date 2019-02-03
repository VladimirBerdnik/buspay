<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class CreateLarametricsNotificationsTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('larametrics_notifications', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('action');
            $table->string('filter')->nullable();
            $table->text('meta')->nullable();
            $table->string('notify_by')->default('email');
            $table->timestamp('last_fired_at')->nullable();
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
        Schema::dropIfExists('larametrics_notifications');
    }
}

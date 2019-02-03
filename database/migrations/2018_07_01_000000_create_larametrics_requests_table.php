<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class CreateLarametricsRequestsTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('larametrics_requests', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('method');
            $table->text('uri');
            $table->string('ip')->nullable();
            $table->text('headers')->nullable();
            $table->float('start_time', 16, 4)->nullable();
            $table->float('end_time', 16, 4)->nullable();
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
        Schema::dropIfExists('larametrics_requests');
    }
}

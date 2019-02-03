<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddRoutesTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table): void {
            $table->increments('id')->comment('Route unique identifier');
            $table->string('name', 16)->comment('Route name AKA "bus number"');
            $table->unsignedInteger('company_id')
                ->nullable()
                ->comment('Currently assigned to route company identifier');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'deleted_at'], 'routes_main_unique');
        });

        $this->commentTable('routes', 'Regular bus routes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
}

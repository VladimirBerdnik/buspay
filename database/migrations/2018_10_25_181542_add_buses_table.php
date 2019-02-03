<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddBusesTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table): void {
            $table->increments('id')->comment('Bus unique identifier');
            $table->unsignedInteger('company_id')->comment('Company identifier, to which this bus belongs');
            $table->string('model_name', 24)->comment('Name of bus model');
            $table->string('state_number', 10)->comment('Bus state number');
            $table->unsignedInteger('route_id')->nullable()->comment('Usual route identifier, on which this bus is');
            $table->tinyInteger('active')
                ->nullable()
                ->comment('Does this bus works or not, can be assigned to route or not');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['state_number', 'deleted_at'], 'buses_main_unique');
        });

        $this->commentTable('buses', 'Buses - assets of transport companies. Can serve route');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
}

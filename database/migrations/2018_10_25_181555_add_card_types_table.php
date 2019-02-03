<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddCardTypesTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('card_types', function (Blueprint $table): void {
            $table->unsignedTinyInteger('id', true)->comment('Type unique identifier');
            $table->string('slug', 32)->comment('Type machine-readable text identifier');

            $table->softDeletes();

            $table->unique(['slug', 'deleted_at'], 'card_types_main_unique');
        });

        $this->commentTable('card_types', 'Possible authenticated card types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('card_types');
    }
}

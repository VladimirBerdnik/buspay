<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddTransactionsTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table): void {
            $table->bigIncrements('id')->comment('Transaction unique identifier');
            $table->timestamp('authorized_at')->comment('Date when card was authorized');
            $table->unsignedInteger('card_id')->comment('Authorized card identifier');
            $table->unsignedInteger('validator_id')->comment('Validator on which card was authorized');
            $table->unsignedSmallInteger('tariff_id')
                ->nullable()
                ->comment('Tariff identifier with which card was authorized');
            $table->smallInteger('amount')->nullable()->comment('Tariff amount that was written-off from card');
            $table->unsignedBigInteger('external_id')->comment('Identifier of transaction in external storage');

            $table->foreign(['card_id'])->references('id')->on('cards');
            $table->foreign(['validator_id'])->references('id')->on('validators');
            $table->foreign(['tariff_id'])->references('id')->on('tariffs');
        });

        $this->commentTable(
            'transactions',
            'Transport card authorization on validator device with write-off amount from  balance'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::create('transactions', function (Blueprint $table): void {
            $table->dropForeign(['card_id']);
            $table->dropForeign(['validator_id']);
            $table->dropForeign(['tariff_id']);
        });
        Schema::dropIfExists('transactions');
    }
}

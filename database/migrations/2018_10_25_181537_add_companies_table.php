<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddCompaniesTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table): void {
            $table->increments('id')->comment('Company unique identifier');
            $table->string('name', 64)->comment('Company name');
            $table->string('bin', 16)->comment('Business identification number');
            $table->string('account_number', 24)->comment('Account number for payments');
            $table->string('contact_information')->comment('Company contact information');

            $table->timestamps();
            $table->softDeletes();
        });

        $this->commentTable('companies', 'Transport companies with buses');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
}

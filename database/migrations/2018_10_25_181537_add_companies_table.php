<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id')->comment('Company unique identifier');
            $table->string('name', 64)->comment('Company name');
            $table->string('bin', 16)->comment('Business identification number');
            $table->string('account_number', 24)->comment('Account number for payments');
            $table->string('contact_information')->comment('Company contact information');

            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `companies` comment 'Transport companies with buses'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}

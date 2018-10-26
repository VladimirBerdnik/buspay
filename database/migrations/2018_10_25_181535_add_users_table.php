<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('User unique identifier');
            $table->unsignedInteger('role_id')->comment('User role identifier');
            $table->unsignedInteger('company_id')->nullable()->comment('Company identifier in which user works');
            $table->string('first_name')->comment('User first name');
            $table->string('last_name')->comment('User last name');
            $table->string('email')->comment('User email address');
            $table->string('password')->comment('User password');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email', 'deleted_at'], 'users_main_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

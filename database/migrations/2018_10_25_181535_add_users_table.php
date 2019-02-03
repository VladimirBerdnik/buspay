<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Utils\CommentsTablesMigration;

class AddUsersTable extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->increments('id')->comment('User unique identifier');
            $table->unsignedInteger('role_id')->comment('User role identifier');
            $table->unsignedInteger('company_id')->nullable()->comment('Company identifier in which user works');
            $table->string('first_name')->comment('User first name');
            $table->string('last_name')->comment('User last name');
            $table->string('email')->comment('User email address');
            $table->string('password')->comment('User password');
            $table->string('remember_token')->nullable()->comment('Authorization token');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email', 'deleted_at'], 'users_main_unique');
        });

        $this->commentTable('users', 'User of application');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}

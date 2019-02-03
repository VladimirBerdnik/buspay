<?php

use Carbon\Carbon;
use Utils\CommentsTablesMigration;

class AddSuperadminUser extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $userDetails = [
            'role_id' => DB::table('roles')->where('slug', 'admin')->first()->id,
            'company_id' => null,
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => env('INITIAL_ADMIN_EMAIL'),
            'password' => password_hash(env('INITIAL_ADMIN_PASSWORD'), PASSWORD_DEFAULT),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        DB::table('users')->insert($userDetails);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table('users')->where('email', env('INITIAL_ADMIN_EMAIL'))->delete();
    }
}

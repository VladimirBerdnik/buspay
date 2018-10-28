<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddSuperadminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userDetails = [
            User::ROLE_ID => DB::table('roles')->where('slug', 'admin')->first()->id,
            User::COMPANY_ID => null,
            User::FIRST_NAME => 'Admin',
            User::LAST_NAME => 'Admin',
            User::EMAIL => env('INITIAL_ADMIN_EMAIL'),
            User::PASSWORD => password_hash(env('INITIAL_ADMIN_PASSWORD'), PASSWORD_DEFAULT),
        ];
        DB::table('users')->insert($userDetails);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')->where('email', env('INITIAL_ADMIN_EMAIL'))->delete();
    }
}

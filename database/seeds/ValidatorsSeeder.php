<?php

use App\Models\Validator;
use Illuminate\Database\Seeder;

class ValidatorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Validator::class, 400)->create();
    }
}

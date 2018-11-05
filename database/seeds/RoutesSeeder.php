<?php

use App\Models\Company;
use App\Models\Route;
use Illuminate\Database\Seeder;

class RoutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Route::class, 20)->make(function (Route $route) {
            if (random_int(0, 1)) {
                $route->company_id = Company::query()->inRandomOrder()->first()->getKey();
            }
            $route->save();
        });
    }
}

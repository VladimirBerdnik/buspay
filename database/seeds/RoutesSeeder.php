<?php

use App\Models\CompaniesRoute;
use App\Models\Company;
use App\Models\Route;
use Carbon\Carbon;
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
        factory(Route::class, 20)->create()->each(function (Route $route) {
            if (random_int(0, 3)) {
                $route->company_id = Company::query()->inRandomOrder()->first()->getKey();
                CompaniesRoute::query()->create([
                    CompaniesRoute::COMPANY_ID => $route->company_id,
                    CompaniesRoute::ROUTE_ID => $route->id,
                    CompaniesRoute::ACTIVE_FROM => Carbon::now(),
                ]);
                $route->save();
            }
        });
    }
}

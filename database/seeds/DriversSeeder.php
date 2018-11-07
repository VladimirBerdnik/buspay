<?php

use App\Models\Company;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriversSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::query()->get()->each(function (Company $company) {
            factory(Driver::class, random_int(20, 50))->make([])
                ->each(function (Driver $driver) use ($company) {
                    $driver->bus_id = random_int(0, 6) ? $company->buses()->inRandomOrder()->first()->getKey() : null;
                    $driver->company_id = $company->getKey();
                    $driver->save();
                });
        });
    }
}

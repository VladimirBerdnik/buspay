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
            factory(Driver::class, random_int(20, 50))->create([
                Driver::COMPANY_ID => $company->getKey(),
                Driver::BUS_ID => random_int(0, 3) ? $company->buses()->inRandomOrder()->first()->getKey() : null,
            ]);
        });
    }
}

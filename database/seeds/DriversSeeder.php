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
            factory(Driver::class, 20)->create([Driver::COMPANY_ID => $company->getKey()]);
        });
    }
}

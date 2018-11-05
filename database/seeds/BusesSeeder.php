<?php

use App\Models\Bus;
use App\Models\Company;
use Illuminate\Database\Seeder;

class BusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::query()->get()->each(function (Company $company) {
            factory(Bus::class, random_int(10, 40))->create([Bus::COMPANY_ID => $company->getKey()]);
        });
    }
}

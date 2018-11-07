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
            factory(Bus::class, random_int(10, 40))
                ->make([Bus::COMPANY_ID => $company->getKey()])
                ->each(function (Bus $bus) use ($company) {
                    $bus->route_id = random_int(0, 3)
                        ? $company->routes()->inRandomOrder()->firstOrNew([])->getKey()
                        : null;

                    $bus->save();
                });
        });
    }
}

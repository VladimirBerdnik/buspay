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
                    if (random_int(0, 3)) {
                        $bus->route_id = $company->routes()->inRandomOrder()->firstOrNew([])->getKey();
                    }

                    $bus->save();
                });
        });
    }
}

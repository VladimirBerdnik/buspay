<?php

use App\Models\Bus;
use App\Models\Company;
use App\Models\Route;
use App\Models\RouteSheet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RouteSheetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // For each company
        Company::query()->get()->each(function (Company $company) {
            // For each company route
            $company->routes->each(function (Route $route) use ($company) {
                // For each route bus
                $route->buses->each(function (Bus $bus) use ($route, $company) {
                    // For first bus driver
                    $driver = $bus->drivers->first();
                    if (!$driver) {
                        return;
                    }
                    // For two week period
                    foreach (range(14, 1) as $daysAgo) {
                        $from = Carbon::now('Asia/Almaty')->subDay($daysAgo)->startOfDay()->addHour(6)->tz('UTC');
                        $to = $from->copy()->addHour(14);
                        // Seed route sheets
                        factory(RouteSheet::class)->create([
                            RouteSheet::COMPANY_ID => $company->id,
                            RouteSheet::ROUTE_ID => $route->id,
                            RouteSheet::BUS_ID => $bus->id,
                            RouteSheet::DRIVER_ID => $driver->id,
                            RouteSheet::ACTIVE_FROM => $from,
                            RouteSheet::ACTIVE_TO => $to,
                        ]);
                    }
                });
            });
        });
    }
}

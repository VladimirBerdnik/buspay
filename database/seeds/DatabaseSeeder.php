<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        try {
            $this->call(TariffSeeder::class);
        } catch (LogicException $exception) {
            // no action required
        }

        $this->call(ValidatorsSeeder::class);
        $this->call(CardsSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(CompanyUsersSeeder::class);
        $this->call(BusesSeeder::class);
        $this->call(DriversSeeder::class);
        $this->call(RoutesSeeder::class);
    }
}

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
        $this->call(CardsSeeder::class);
        $this->call(ReplenishmentSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(CompanyUsersSeeder::class);
        $this->call(RoutesSeeder::class);
        $this->call(BusesSeeder::class);
        $this->call(DriversSeeder::class);
        $this->call(ValidatorsSeeder::class);
        $this->call(RouteSheetsSeeder::class);
    }
}

<?php

use App\Domain\Enums\RolesIdentifiers;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::query()->get()->each(function (Company $company) {
            factory(User::class, random_int(1, 3))->create([
                User::COMPANY_ID => $company->getKey(),
                User::ROLE_ID => RolesIdentifiers::OPERATOR,
            ]);
        });
    }
}

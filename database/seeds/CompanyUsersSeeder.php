<?php

use App\Domain\Enums\Roles;
use App\Models\Company;
use App\Models\Role;
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
        $operatorRole = Role::query()->where(Role::SLUG, Roles::OPERATOR)->firstOrFail();

        Company::query()->get()->each(function (Company $company) use ($operatorRole) {
            factory(User::class, 2)->create([
                User::COMPANY_ID => $company->getKey(),
                User::ROLE_ID => $operatorRole->getKey(),
            ]);
        });
    }
}

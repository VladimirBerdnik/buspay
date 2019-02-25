<?php

namespace App\Providers;

use App\Models\Bus;
use App\Models\Card;
use App\Models\CardType;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Replenishment;
use App\Models\Role;
use App\Models\Route;
use App\Models\RouteSheet;
use App\Models\Tariff;
use App\Models\TariffFare;
use App\Models\TariffPeriod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Validator;
use App\Policies\BusEntityPolicy;
use App\Policies\CardEntityPolicy;
use App\Policies\DriverEntityPolicy;
use App\Policies\EntityTypePolicy;
use App\Policies\TransactionEntityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Registers application permissions policies.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var string[]
     */
    protected $policies = [
        Bus::class => BusEntityPolicy::class,
        CardType::class => EntityTypePolicy::class,
        Card::class => CardEntityPolicy::class,
        Company::class => EntityTypePolicy::class,
        Driver::class => DriverEntityPolicy::class,
        Role::class => EntityTypePolicy::class,
        RouteSheet::class => EntityTypePolicy::class,
        Route::class => EntityTypePolicy::class,
        Tariff::class => EntityTypePolicy::class,
        TariffFare::class => EntityTypePolicy::class,
        TariffPeriod::class => EntityTypePolicy::class,
        User::class => EntityTypePolicy::class,
        Validator::class => EntityTypePolicy::class,
        Replenishment::class => EntityTypePolicy::class,
        Transaction::class => TransactionEntityPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

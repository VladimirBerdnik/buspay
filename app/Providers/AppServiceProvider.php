<?php

namespace App\Providers;

use App\Domain\Services\BusesValidatorService;
use App\Domain\Services\BusService;
use App\Domain\Services\CardService;
use App\Domain\Services\CardTypeService;
use App\Domain\Services\CompaniesRouteService;
use App\Domain\Services\CompanyService;
use App\Domain\Services\DriversCardService;
use App\Domain\Services\DriverService;
use App\Domain\Services\RoleService;
use App\Domain\Services\RouteService;
use App\Domain\Services\RouteSheetService;
use App\Domain\Services\TariffFareService;
use App\Domain\Services\TariffService;
use App\Domain\Services\UserService;
use App\Domain\Services\ValidatorService;
use App\Exceptions\ApiExceptionHandler;
use App\Repositories\BusesValidatorRepository;
use App\Repositories\BusRepository;
use App\Repositories\CardRepository;
use App\Repositories\CardTypeRepository;
use App\Repositories\CompaniesRouteRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\DriverRepository;
use App\Repositories\DriversCardRepository;
use App\Repositories\RoleRepository;
use App\Repositories\RouteRepository;
use App\Repositories\RouteSheetRepository;
use App\Repositories\TariffFareRepository;
use App\Repositories\TariffRepository;
use App\Repositories\UserRepository;
use App\Repositories\ValidatorRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Saritasa\LaravelRepositories\Contracts\IRepository;

/**
 * Provider with specific for this application settings.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton('app.api.exception', ApiExceptionHandler::class);
        // Fix for MySQL 5.6. See https://github.com/laravel/framework/issues/17508
        Schema::defaultStringLength(191);

        $this->app->when(BusesValidatorService::class)
            ->needs(IRepository::class)
            ->give(BusesValidatorRepository::class);
        $this->app->when(CompaniesRouteService::class)
            ->needs(IRepository::class)
            ->give(CompaniesRouteRepository::class);
        $this->app->when(CardService::class)->needs(IRepository::class)->give(CardRepository::class);
        $this->app->when(CardTypeService::class)->needs(IRepository::class)->give(CardTypeRepository::class);
        $this->app->when(BusService::class)->needs(IRepository::class)->give(BusRepository::class);
        $this->app->when(CompanyService::class)->needs(IRepository::class)->give(CompanyRepository::class);
        $this->app->when(DriverService::class)->needs(IRepository::class)->give(DriverRepository::class);
        $this->app->when(DriversCardService::class)->needs(IRepository::class)->give(DriversCardRepository::class);
        $this->app->when(RoleService::class)->needs(IRepository::class)->give(RoleRepository::class);
        $this->app->when(RouteService::class)->needs(IRepository::class)->give(RouteRepository::class);
        $this->app->when(RouteSheetService::class)->needs(IRepository::class)->give(RouteSheetRepository::class);
        $this->app->when(TariffService::class)->needs(IRepository::class)->give(TariffRepository::class);
        $this->app->when(TariffFareService::class)->needs(IRepository::class)->give(TariffFareRepository::class);
        $this->app->when(UserService::class)->needs(IRepository::class)->give(UserRepository::class);
        $this->app->when(ValidatorService::class)->needs(IRepository::class)->give(ValidatorRepository::class);
    }
}

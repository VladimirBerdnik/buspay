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
use App\Domain\Services\TariffPeriodService;
use App\Domain\Services\TariffService;
use App\Domain\Services\UserService;
use App\Domain\Services\ValidatorService;
use App\Exceptions\ApiExceptionHandler;
use App\Http\Controllers\Api\v1\CardTypesApiController;
use App\Http\Controllers\Api\v1\CompaniesApiController;
use App\Http\Controllers\Api\v1\ProfileApiController;
use App\Http\Controllers\Api\v1\TariffPeriodsApiController;
use App\Http\Controllers\Api\v1\TariffsApiController;
use App\Http\Transformers\Api\CardTypeTransformer;
use App\Http\Transformers\Api\CompanyTransformer;
use App\Http\Transformers\Api\ProfileTransformer;
use App\Http\Transformers\Api\TariffPeriodTransformer;
use App\Http\Transformers\Api\TariffTransformer;
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
use App\Repositories\TariffPeriodRepository;
use App\Repositories\TariffRepository;
use App\Repositories\UserRepository;
use App\Repositories\ValidatorRepository;
use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Saritasa\LaravelRepositories\Contracts\IRepository;
use Saritasa\Transformers\IDataTransformer;

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

        $this->registerBindings();

        /**
         * Dingo API transformer response factory.
         *
         * @var Fractal $dingoApiTransformerFactory
         */
        $dingoApiTransformerFactory = $this->app['api.transformer'];
        // Disable automatic eager loading model relations for requested includes in response transformer
        $dingoApiTransformerFactory->disableEagerLoading();
    }

    /**
     * Registers dependencies bindings.
     *
     * @return void
     */
    private function registerBindings(): void
    {
        // Register repositories bindings
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
        $this->app->when(TariffPeriodService::class)->needs(IRepository::class)->give(TariffPeriodRepository::class);
        $this->app->when(TariffFareService::class)->needs(IRepository::class)->give(TariffFareRepository::class);
        $this->app->when(UserService::class)->needs(IRepository::class)->give(UserRepository::class);
        $this->app->when(ValidatorService::class)->needs(IRepository::class)->give(ValidatorRepository::class);

        // Register transformers bindings
        $this->app->when(ProfileApiController::class)->needs(IDataTransformer::class)->give(ProfileTransformer::class);
        $this->app->when(CardTypesApiController::class)
            ->needs(IDataTransformer::class)
            ->give(CardTypeTransformer::class);
        $this->app->when(TariffsApiController::class)->needs(IDataTransformer::class)->give(TariffTransformer::class);
        $this->app->when(TariffPeriodsApiController::class)
            ->needs(IDataTransformer::class)
            ->give(TariffPeriodTransformer::class);
        $this->app->when(CompaniesApiController::class)
            ->needs(IDataTransformer::class)
            ->give(CompanyTransformer::class);
    }
}

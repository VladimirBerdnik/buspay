<?php

namespace App\Providers;

use App\Domain\EntitiesServices\BusEntityService;
use App\Domain\EntitiesServices\BusesValidatorEntityService;
use App\Domain\EntitiesServices\CardEntityService;
use App\Domain\EntitiesServices\CardTypeEntityService;
use App\Domain\EntitiesServices\CompaniesRouteEntityService;
use App\Domain\EntitiesServices\CompanyEntityService;
use App\Domain\EntitiesServices\DriverEntityService;
use App\Domain\EntitiesServices\DriversCardEntityService;
use App\Domain\EntitiesServices\ReplenishmentEntityService;
use App\Domain\EntitiesServices\RoleEntityService;
use App\Domain\EntitiesServices\RouteEntityService;
use App\Domain\EntitiesServices\RouteSheetEntityService;
use App\Domain\EntitiesServices\TariffEntityService;
use App\Domain\EntitiesServices\TariffFareEntityService;
use App\Domain\EntitiesServices\TariffPeriodEntityService;
use App\Domain\EntitiesServices\TransactionEntityService;
use App\Domain\EntitiesServices\UserEntityService;
use App\Domain\EntitiesServices\ValidatorEntityService;
use App\Domain\Import\CardsImporter;
use App\Domain\Import\ReplenishmentImporter;
use App\Domain\Import\ValidatorsImporter;
use App\Exceptions\ApiExceptionHandler;
use App\Http\Controllers\Api\v1\BusesApiController;
use App\Http\Controllers\Api\v1\CardBalanceApiController;
use App\Http\Controllers\Api\v1\CardsApiController;
use App\Http\Controllers\Api\v1\CardTypesApiController;
use App\Http\Controllers\Api\v1\CompaniesApiController;
use App\Http\Controllers\Api\v1\DriversApiController;
use App\Http\Controllers\Api\v1\ProfileApiController;
use App\Http\Controllers\Api\v1\ReplenishmentsApiController;
use App\Http\Controllers\Api\v1\RolesApiController;
use App\Http\Controllers\Api\v1\RoutesApiController;
use App\Http\Controllers\Api\v1\RouteSheetsApiController;
use App\Http\Controllers\Api\v1\TariffPeriodsApiController;
use App\Http\Controllers\Api\v1\TariffsApiController;
use App\Http\Controllers\Api\v1\UsersApiController;
use App\Http\Controllers\Api\v1\ValidatorsApiController;
use App\Http\Transformers\Api\BusTransformer;
use App\Http\Transformers\Api\CardBalanceTransactionTransformer;
use App\Http\Transformers\Api\CardTransformer;
use App\Http\Transformers\Api\CardTypeTransformer;
use App\Http\Transformers\Api\CompanyTransformer;
use App\Http\Transformers\Api\DriverTransformer;
use App\Http\Transformers\Api\ProfileTransformer;
use App\Http\Transformers\Api\ReplenishmentTransformer;
use App\Http\Transformers\Api\RoleTransformer;
use App\Http\Transformers\Api\RouteSheetTransformer;
use App\Http\Transformers\Api\RouteTransformer;
use App\Http\Transformers\Api\TariffPeriodTransformer;
use App\Http\Transformers\Api\TariffTransformer;
use App\Http\Transformers\Api\ValidatorTransformer;
use App\Repositories\BusesValidatorRepository;
use App\Repositories\BusRepository;
use App\Repositories\CardRepository;
use App\Repositories\CardTypeRepository;
use App\Repositories\CompaniesRouteRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\DriverRepository;
use App\Repositories\DriversCardRepository;
use App\Repositories\ReplenishmentRepository;
use App\Repositories\RoleRepository;
use App\Repositories\RouteRepository;
use App\Repositories\RouteSheetRepository;
use App\Repositories\TariffFareRepository;
use App\Repositories\TariffPeriodRepository;
use App\Repositories\TariffRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Repositories\ValidatorRepository;
use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Log;
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

        Log::listen(function (MessageLogged $message): void {
            $levelLoggable = in_array($message->level, config('sentry.capturable_log_levels'), true);
            if ($levelLoggable && config('sentry.enabled')) {
                $message->context['level'] = $message->level;
                app('sentry')->captureMessage($message->message, [], $message->context);
            }
        });
    }

    /**
     * Registers dependencies bindings.
     *
     * @return void
     */
    private function registerBindings(): void
    {
        $this->app->when(ReplenishmentImporter::class)->needs(ConnectionInterface::class)->give(function () {
            return DB::connection('external');
        });
        $this->app->when(CardsImporter::class)->needs(ConnectionInterface::class)->give(function () {
            return DB::connection('external');
        });
        $this->app->when(ValidatorsImporter::class)->needs(ConnectionInterface::class)->give(function () {
            return DB::connection('external');
        });

        // Register repositories bindings
        $this->app->when(BusesValidatorEntityService::class)
            ->needs(IRepository::class)
            ->give(BusesValidatorRepository::class);
        $this->app->when(CompaniesRouteEntityService::class)
            ->needs(IRepository::class)
            ->give(CompaniesRouteRepository::class);
        $this->app->when(CardEntityService::class)->needs(IRepository::class)->give(CardRepository::class);
        $this->app->when(CardTypeEntityService::class)->needs(IRepository::class)->give(CardTypeRepository::class);
        $this->app->when(BusEntityService::class)->needs(IRepository::class)->give(BusRepository::class);
        $this->app->when(CompanyEntityService::class)->needs(IRepository::class)->give(CompanyRepository::class);
        $this->app->when(DriverEntityService::class)->needs(IRepository::class)->give(DriverRepository::class);
        $this->app->when(DriversCardEntityService::class)
            ->needs(IRepository::class)
            ->give(DriversCardRepository::class);
        $this->app->when(RoleEntityService::class)->needs(IRepository::class)->give(RoleRepository::class);
        $this->app->when(RouteEntityService::class)->needs(IRepository::class)->give(RouteRepository::class);
        $this->app->when(RouteSheetEntityService::class)->needs(IRepository::class)->give(RouteSheetRepository::class);
        $this->app->when(TariffEntityService::class)->needs(IRepository::class)->give(TariffRepository::class);
        $this->app->when(TariffPeriodEntityService::class)
            ->needs(IRepository::class)
            ->give(TariffPeriodRepository::class);
        $this->app->when(TariffFareEntityService::class)->needs(IRepository::class)->give(TariffFareRepository::class);
        $this->app->when(UserEntityService::class)->needs(IRepository::class)->give(UserRepository::class);
        $this->app->when(ValidatorEntityService::class)->needs(IRepository::class)->give(ValidatorRepository::class);
        $this->app->when(ReplenishmentEntityService::class)
            ->needs(IRepository::class)
            ->give(ReplenishmentRepository::class);
        $this->app->when(TransactionEntityService::class)
            ->needs(IRepository::class)
            ->give(TransactionRepository::class);

        // Register transformers bindings
        $this->app->when(ProfileApiController::class)->needs(IDataTransformer::class)->give(ProfileTransformer::class);
        $this->app->when(UsersApiController::class)->needs(IDataTransformer::class)->give(ProfileTransformer::class);
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
        $this->app->when(RolesApiController::class)->needs(IDataTransformer::class)->give(RoleTransformer::class);
        $this->app->when(RoutesApiController::class)->needs(IDataTransformer::class)->give(RouteTransformer::class);
        $this->app->when(BusesApiController::class)->needs(IDataTransformer::class)->give(BusTransformer::class);
        $this->app->when(DriversApiController::class)->needs(IDataTransformer::class)->give(DriverTransformer::class);
        $this->app->when(CardsApiController::class)->needs(IDataTransformer::class)->give(CardTransformer::class);
        $this->app->when(ValidatorsApiController::class)
            ->needs(IDataTransformer::class)
            ->give(ValidatorTransformer::class);
        $this->app->when(RouteSheetsApiController::class)
            ->needs(IDataTransformer::class)
            ->give(RouteSheetTransformer::class);
        $this->app->when(CardBalanceApiController::class)
            ->needs(IDataTransformer::class)
            ->give(CardBalanceTransactionTransformer::class);
        $this->app->when(ReplenishmentsApiController::class)
            ->needs(IDataTransformer::class)
            ->give(ReplenishmentTransformer::class);
    }
}

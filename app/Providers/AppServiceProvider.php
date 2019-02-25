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
use App\Domain\Import\ReplenishmentsVerifier;
use App\Domain\Import\TransactionsImporter;
use App\Domain\Import\TransactionsVerifier;
use App\Domain\Import\ValidatorsImporter;
use App\Domain\Import\ValidatorsVerifier;
use App\Exceptions\ApiExceptionHandler;
use App\Extensions\EntityService;
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
use App\Http\Controllers\Api\v1\TariffFaresApiController;
use App\Http\Controllers\Api\v1\TariffPeriodsApiController;
use App\Http\Controllers\Api\v1\TariffsApiController;
use App\Http\Controllers\Api\v1\TransactionsApiController;
use App\Http\Controllers\Api\v1\UsersApiController;
use App\Http\Controllers\Api\v1\ValidatorsApiController;
use App\Http\Transformers\Api\BusTransformer;
use App\Http\Transformers\Api\CardBalanceRecordTransformer;
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
use App\Http\Transformers\Api\TransactionTransformer;
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
use Illuminate\Support\Facades\DB;
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
        $this->registerImporterBindings();

        $this->registerTransformersBindings();

        $this->registerRepositoriesBindings();
    }

    /**
     * Registers bindings that are involved into import entities process.
     */
    private function registerImporterBindings(): void
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
        $this->app->when(TransactionsImporter::class)->needs(ConnectionInterface::class)->give(function () {
            return DB::connection('external');
        });

        $this->app->when(ValidatorsVerifier::class)->needs(ConnectionInterface::class)->give(function () {
            return DB::connection('external');
        });
        $this->app->when(ValidatorsVerifier::class)->needs(EntityService::class)->give(function () {
            return $this->app->make(ValidatorEntityService::class);
        });

        $this->app->when(TransactionsVerifier::class)->needs(ConnectionInterface::class)->give(function () {
            return DB::connection('external');
        });
        $this->app->when(TransactionsVerifier::class)->needs(EntityService::class)->give(function () {
            return $this->app->make(TransactionEntityService::class);
        });

        $this->app->when(ReplenishmentsVerifier::class)->needs(ConnectionInterface::class)->give(function () {
            return DB::connection('external');
        });
        $this->app->when(ReplenishmentsVerifier::class)->needs(EntityService::class)->give(function () {
            return $this->app->make(ReplenishmentEntityService::class);
        });
    }

    /**
     * Registers transformers bindings.
     */
    private function registerTransformersBindings(): void
    {
        $transformer = IDataTransformer::class;
        $app = $this->app;

        $app->when(BusesApiController::class)->needs($transformer)->give(BusTransformer::class);
        $app->when(CardBalanceApiController::class)->needs($transformer)->give(CardBalanceRecordTransformer::class);
        $app->when(CardTypesApiController::class)->needs($transformer)->give(CardTypeTransformer::class);
        $app->when(CardsApiController::class)->needs($transformer)->give(CardTransformer::class);
        $app->when(CompaniesApiController::class)->needs($transformer)->give(CompanyTransformer::class);
        $app->when(DriversApiController::class)->needs($transformer)->give(DriverTransformer::class);
        $app->when(ProfileApiController::class)->needs($transformer)->give(ProfileTransformer::class);
        $app->when(ReplenishmentsApiController::class)->needs($transformer)->give(ReplenishmentTransformer::class);
        $app->when(RolesApiController::class)->needs($transformer)->give(RoleTransformer::class);
        $app->when(RouteSheetsApiController::class)->needs($transformer)->give(RouteSheetTransformer::class);
        $app->when(RoutesApiController::class)->needs($transformer)->give(RouteTransformer::class);
        $app->when(TariffPeriodsApiController::class)->needs($transformer)->give(TariffPeriodTransformer::class);
        $app->when(TariffsApiController::class)->needs($transformer)->give(TariffTransformer::class);
        $app->when(TariffFaresApiController::class)->needs($transformer)->give(TariffTransformer::class);
        $app->when(TransactionsApiController::class)->needs($transformer)->give(TransactionTransformer::class);
        $app->when(UsersApiController::class)->needs($transformer)->give(ProfileTransformer::class);
        $app->when(ValidatorsApiController::class)->needs($transformer)->give(ValidatorTransformer::class);
    }

    /**
     * Registers repositories bindings.
     */
    private function registerRepositoriesBindings(): void
    {
        $repository = IRepository::class;
        $app = $this->app;

        $app->when(BusEntityService::class)->needs($repository)->give(BusRepository::class);
        $app->when(BusesValidatorEntityService::class)->needs($repository)->give(BusesValidatorRepository::class);
        $app->when(CardEntityService::class)->needs($repository)->give(CardRepository::class);
        $app->when(CardTypeEntityService::class)->needs($repository)->give(CardTypeRepository::class);
        $app->when(CompaniesRouteEntityService::class)->needs($repository)->give(CompaniesRouteRepository::class);
        $app->when(CompanyEntityService::class)->needs($repository)->give(CompanyRepository::class);
        $app->when(DriverEntityService::class)->needs($repository)->give(DriverRepository::class);
        $app->when(DriversCardEntityService::class)->needs($repository)->give(DriversCardRepository::class);
        $app->when(ReplenishmentEntityService::class)->needs($repository)->give(ReplenishmentRepository::class);
        $app->when(RoleEntityService::class)->needs($repository)->give(RoleRepository::class);
        $app->when(RouteEntityService::class)->needs($repository)->give(RouteRepository::class);
        $app->when(RouteSheetEntityService::class)->needs($repository)->give(RouteSheetRepository::class);
        $app->when(TariffEntityService::class)->needs($repository)->give(TariffRepository::class);
        $app->when(TariffFareEntityService::class)->needs($repository)->give(TariffFareRepository::class);
        $app->when(TariffPeriodEntityService::class)->needs($repository)->give(TariffPeriodRepository::class);
        $app->when(TransactionEntityService::class)->needs($repository)->give(TransactionRepository::class);
        $app->when(UserEntityService::class)->needs($repository)->give(UserRepository::class);
        $app->when(ValidatorEntityService::class)->needs($repository)->give(ValidatorRepository::class);
    }
}

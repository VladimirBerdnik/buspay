<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\v1\BusesApiController;
use App\Http\Controllers\Api\v1\CardBalanceApiController;
use App\Http\Controllers\Api\v1\CardsApiController;
use App\Http\Controllers\Api\v1\CardTypesApiController;
use App\Http\Controllers\Api\v1\CompaniesApiController;
use App\Http\Controllers\Api\v1\DriversApiController;
use App\Http\Controllers\Api\v1\PoliciesApiController;
use App\Http\Controllers\Api\v1\ProfileApiController;
use App\Http\Controllers\Api\v1\ReplenishmentsApiController;
use App\Http\Controllers\Api\v1\RolesApiController;
use App\Http\Controllers\Api\v1\RoutesApiController;
use App\Http\Controllers\Api\v1\RouteSheetsApiController;
use App\Http\Controllers\Api\v1\TariffPeriodsApiController;
use App\Http\Controllers\Api\v1\TariffsApiController;
use App\Http\Controllers\Api\v1\TransactionsApiController;
use App\Http\Controllers\Api\v1\UsersApiController;
use App\Http\Controllers\Api\v1\ValidatorsApiController;
use App\Models\Bus;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Route;
use App\Models\RouteSheet;
use App\Models\User;
use App\Models\Validator;
use Dingo\Api\Routing\Router;
use Saritasa\LaravelControllers\Api\ApiResourceRegistrar;
use Saritasa\LaravelControllers\Api\ForgotPasswordApiController;
use Saritasa\LaravelControllers\Api\JWTAuthApiController;
use Saritasa\LaravelControllers\Api\ResetPasswordApiController;

/**
 * Api router instance.
 *
 * @var Router $api
 */
$api = app(Router::class);
$api->version(config('api.version'), ['middleware' => 'bindings'], function (Router $api): void {
    $registrar = new ApiResourceRegistrar($api);

    // Authentication related routes
    $registrar->post('auth', JWTAuthApiController::class, 'login');
    $registrar->put('auth', JWTAuthApiController::class, 'refreshToken');
    $registrar->post('auth/password/reset', ForgotPasswordApiController::class, 'sendResetLinkEmail');
    $registrar->put('auth/password/reset', ResetPasswordApiController::class, 'reset');

    // Card balance requests. Only 60 requests in 10 minutes allowed
    $api->group(['middleware' => 'api.throttle', 'limit' => 60, 'expires' => 10], function (Router $api): void {
        $registrar = new ApiResourceRegistrar($api);

        $registrar->get('/cardBalance/{card_number}/total', CardBalanceApiController::class, 'total');
        $registrar->get('/cardBalance/{card_number}/transactions', CardBalanceApiController::class, 'transactions');
    });

    // Group of routes that require authentication
    $api->group(['middleware' => ['jwt.auth']], function (Router $api): void {
        $registrar = new ApiResourceRegistrar($api);

        // Authentication related routes
        $registrar->get('me', ProfileApiController::class, 'me');
        $registrar->delete('auth', JWTAuthApiController::class, 'logout');

        // Policies list
        $registrar->get('policies', PoliciesApiController::class, ApiResourceRegistrar::ACTION_INDEX);

        // Card types related routes
        $registrar->get('cardTypes', CardTypesApiController::class, ApiResourceRegistrar::ACTION_INDEX);

        // Roles related routes
        $registrar->get('roles', RolesApiController::class, ApiResourceRegistrar::ACTION_INDEX);

        // Tariffs related routes
        $registrar->get(
            'tariffPeriods/{tariffPeriod}/tariffs',
            TariffsApiController::class,
            ApiResourceRegistrar::ACTION_INDEX
        );
        $registrar->get('tariffPeriods', TariffPeriodsApiController::class, ApiResourceRegistrar::ACTION_INDEX);

        // Companies related routes
        $registrar->resource('companies', CompaniesApiController::class, [
            ApiResourceRegistrar::OPTION_ONLY => [
                ApiResourceRegistrar::ACTION_INDEX,
                ApiResourceRegistrar::ACTION_CREATE,
                ApiResourceRegistrar::ACTION_UPDATE,
                ApiResourceRegistrar::ACTION_DESTROY,
            ],
        ], Company::class);

        // Users related routes
        $registrar->resource('users', UsersApiController::class, [
            ApiResourceRegistrar::OPTION_ONLY => [
                ApiResourceRegistrar::ACTION_INDEX,
                ApiResourceRegistrar::ACTION_CREATE,
                ApiResourceRegistrar::ACTION_UPDATE,
                ApiResourceRegistrar::ACTION_DESTROY,
            ],
        ], User::class);

        // Route related routes
        $registrar->resource('routes', RoutesApiController::class, [
            ApiResourceRegistrar::OPTION_ONLY => [
                ApiResourceRegistrar::ACTION_INDEX,
                ApiResourceRegistrar::ACTION_CREATE,
                ApiResourceRegistrar::ACTION_UPDATE,
                ApiResourceRegistrar::ACTION_DESTROY,
            ],
        ], Route::class);

        // Bus related routes
        $registrar->resource('buses', BusesApiController::class, [
            ApiResourceRegistrar::OPTION_ONLY => [
                ApiResourceRegistrar::ACTION_INDEX,
                ApiResourceRegistrar::ACTION_CREATE,
                ApiResourceRegistrar::ACTION_UPDATE,
                ApiResourceRegistrar::ACTION_DESTROY,
            ],
        ], Bus::class);

        // Driver related routes
        $registrar->resource('drivers', DriversApiController::class, [
            ApiResourceRegistrar::OPTION_ONLY => [
                ApiResourceRegistrar::ACTION_INDEX,
                ApiResourceRegistrar::ACTION_CREATE,
                ApiResourceRegistrar::ACTION_UPDATE,
                ApiResourceRegistrar::ACTION_DESTROY,
            ],
        ], Driver::class);

        // Validator related routes
        $registrar->resource('validators', ValidatorsApiController::class, [
            ApiResourceRegistrar::OPTION_ONLY => [
                ApiResourceRegistrar::ACTION_INDEX,
                ApiResourceRegistrar::ACTION_UPDATE,
            ],
        ], Validator::class);

        // Card related routes
        $registrar->get('cards/drivers', CardsApiController::class, 'driverCards');
        $registrar->get('cards', CardsApiController::class, ApiResourceRegistrar::ACTION_INDEX);

        // Replenishment related routes
        $registrar->get('replenishments', ReplenishmentsApiController::class, ApiResourceRegistrar::ACTION_INDEX);

        // Transactions related routes
        $registrar->get('transactions', TransactionsApiController::class, ApiResourceRegistrar::ACTION_INDEX);
        $registrar->get('transactions/export', TransactionsApiController::class, 'export');

        // Route sheets related routes
        $registrar->resource('route_sheets', RouteSheetsApiController::class, [
            ApiResourceRegistrar::OPTION_ONLY => [
                ApiResourceRegistrar::ACTION_INDEX,
                ApiResourceRegistrar::ACTION_CREATE,
                ApiResourceRegistrar::ACTION_UPDATE,
                ApiResourceRegistrar::ACTION_DESTROY,
            ],
        ], RouteSheet::class);
        $registrar->get('route_sheets/export', RouteSheetsApiController::class, 'export');
    });
});

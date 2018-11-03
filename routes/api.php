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

use App\Http\Controllers\Api\v1\CardTypesApiController;
use App\Http\Controllers\Api\v1\CompaniesApiController;
use App\Http\Controllers\Api\v1\ProfileApiController;
use App\Http\Controllers\Api\v1\TariffPeriodsApiController;
use App\Http\Controllers\Api\v1\TariffsApiController;
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
$api->version(config('api.version'), ['middleware' => 'bindings'], function (Router $api) {
    $registrar = new ApiResourceRegistrar($api);

    // Authentication related routes
    $registrar->post('auth', JWTAuthApiController::class, 'login');
    $registrar->put('auth', JWTAuthApiController::class, 'refreshToken');
    $registrar->post('auth/password/reset', ForgotPasswordApiController::class, 'sendResetLinkEmail');
    $registrar->put('auth/password/reset', ResetPasswordApiController::class, 'reset');

    // Group of routes that require authentication
    $api->group(['middleware' => ['jwt.auth']], function (Router $api) {
        $registrar = new ApiResourceRegistrar($api);

        // Authentication related routes
        $registrar->get('me', ProfileApiController::class, 'me');
        $registrar->delete('auth', JWTAuthApiController::class, 'logout');

        // Card types related routes
        $registrar->get('cardTypes', CardTypesApiController::class, ApiResourceRegistrar::ACTION_INDEX);

        // Tariffs related routes
        $registrar->get(
            'tariffPeriods/{tariffPeriod}/tariffs',
            TariffsApiController::class,
            ApiResourceRegistrar::ACTION_INDEX
        );
        $registrar->get('tariffPeriods', TariffPeriodsApiController::class, ApiResourceRegistrar::ACTION_INDEX);

        // Companies related routes
        $registrar->get('companies', CompaniesApiController::class, ApiResourceRegistrar::ACTION_INDEX);
    });
});

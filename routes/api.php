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

use Dingo\Api\Routing\Router;
use Saritasa\LaravelControllers\Api\ApiResourceRegistrar;
use Saritasa\LaravelControllers\Api\JWTAuthApiController;
use Saritasa\LaravelControllers\Api\ForgotPasswordApiController;
use Saritasa\LaravelControllers\Api\ResetPasswordApiController;

/**
 * Api router instance.
 *
 * @var Router $api
 */
$api = app(Router::class);
$api->version(config('api.version'), ['middleware' => 'bindings'], function (Router $api) {
    $registrar = new ApiResourceRegistrar($api);

    $registrar->post('auth', JWTAuthApiController::class, 'login');
    $registrar->put('auth', JWTAuthApiController::class, 'refreshToken');

    $registrar->post('auth/password/reset', ForgotPasswordApiController::class, 'sendResetLinkEmail');
    $registrar->put('auth/password/reset', ResetPasswordApiController::class, 'reset');

    // Group of routes that require authentication
    $api->group(['middleware' => ['jwt.auth']], function (Router $api) {
        $registrar = new ApiResourceRegistrar($api);

        $registrar->delete('auth', JWTAuthApiController::class, 'logout');
    });
});

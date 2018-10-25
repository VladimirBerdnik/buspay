<?php

namespace App\Providers;

use App\Exceptions\ApiExceptionHandler;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
    }
}

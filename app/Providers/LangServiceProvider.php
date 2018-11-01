<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Allows to override lang resource namespaces.
 */
class LangServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Override lang resources for 'controllers' namespace
        $this->loadTranslationsFrom(resource_path('langNamespaces/controllers'), 'controllers');
    }
}

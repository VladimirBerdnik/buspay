<?php

namespace App\Providers;

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
    protected $policies = [];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     *
     * @internal param GateContract $gate
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

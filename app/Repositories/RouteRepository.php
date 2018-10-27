<?php

namespace App\Repositories;

use App\Models\Route;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * Route records storage.
 */
class RouteRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Route::class;
}

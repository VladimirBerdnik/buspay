<?php

namespace App\Repositories;

use App\Models\CompaniesRoute;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * CompaniesRoute records storage.
 */
class CompaniesRouteRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = CompaniesRoute::class;
}

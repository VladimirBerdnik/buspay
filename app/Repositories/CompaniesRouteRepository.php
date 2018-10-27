<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\CompaniesRoute;

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

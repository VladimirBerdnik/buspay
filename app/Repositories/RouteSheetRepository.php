<?php

namespace App\Repositories;

use App\Models\RouteSheet;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * RouteSheet records storage.
 */
class RouteSheetRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = RouteSheet::class;
}

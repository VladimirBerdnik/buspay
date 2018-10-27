<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\RouteSheet;

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

<?php

namespace App\Repositories;

use App\Models\Driver;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * Driver records storage.
 */
class DriverRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Driver::class;
}

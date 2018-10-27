<?php

namespace App\Repositories;

use App\Models\Bus;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * Bus records storage.
 */
class BusRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Bus::class;
}

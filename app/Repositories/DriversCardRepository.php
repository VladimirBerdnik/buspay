<?php

namespace App\Repositories;

use App\Models\DriversCard;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * DriversCard records storage.
 */
class DriversCardRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = DriversCard::class;
}

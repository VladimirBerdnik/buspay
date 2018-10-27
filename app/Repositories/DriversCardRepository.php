<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\DriversCard;

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

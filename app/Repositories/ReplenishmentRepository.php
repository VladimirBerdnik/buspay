<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\Replenishment;

/**
 * Card replenishment records storage.
 */
class ReplenishmentRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Replenishment::class;
}

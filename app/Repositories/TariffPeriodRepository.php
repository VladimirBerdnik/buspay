<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\TariffPeriod;

/**
 * TariffPeriod records storage.
 */
class TariffPeriodRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = TariffPeriod::class;
}

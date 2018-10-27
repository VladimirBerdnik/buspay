<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\TariffFare;

/**
 * TariffFare records storage.
 */
class TariffFareRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = TariffFare::class;
}

<?php

namespace App\Repositories;

use App\Models\TariffFare;
use Saritasa\LaravelRepositories\Repositories\Repository;

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

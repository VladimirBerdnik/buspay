<?php

namespace App\Repositories;

use App\Models\Tariff;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * Tariff records storage.
 */
class TariffRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Tariff::class;
}

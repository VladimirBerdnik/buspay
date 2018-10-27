<?php

namespace App\Repositories;

use App\Models\BusesValidator;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * BusesValidator records storage.
 */
class BusesValidatorRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = BusesValidator::class;
}

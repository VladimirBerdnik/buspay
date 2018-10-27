<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\BusesValidator;

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

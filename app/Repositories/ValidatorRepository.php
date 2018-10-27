<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\Validator;

/**
 * Validator records storage.
 */
class ValidatorRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Validator::class;
}

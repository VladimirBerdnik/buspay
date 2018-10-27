<?php

namespace App\Repositories;

use App\Models\Validator;
use Saritasa\LaravelRepositories\Repositories\Repository;

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

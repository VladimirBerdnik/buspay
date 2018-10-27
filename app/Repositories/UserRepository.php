<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\User;

/**
 * User records storage.
 */
class UserRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = User::class;
}

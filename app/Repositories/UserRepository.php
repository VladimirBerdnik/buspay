<?php

namespace App\Repositories;

use App\Models\User;
use Saritasa\LaravelRepositories\Repositories\Repository;

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

<?php

namespace App\Repositories;

use App\Models\Role;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * Role records storage.
 */
class RoleRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Role::class;
}

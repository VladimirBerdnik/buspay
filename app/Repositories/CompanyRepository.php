<?php

namespace App\Repositories;

use App\Models\Company;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * Company records storage.
 */
class CompanyRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Company::class;
}

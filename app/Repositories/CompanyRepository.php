<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\Company;

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

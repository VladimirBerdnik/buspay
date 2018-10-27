<?php

namespace App\Extensions;

use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * {@inheritdoc}
 * Allows to declare handled model class in private property.
 */
class PredefinedModelClassRepository extends Repository
{
    /** {@inheritdoc} */
    public function __construct(?string $modelClass = null)
    {
        parent::__construct($modelClass ?? $this->modelClass);
    }
}

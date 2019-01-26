<?php

namespace App\Extensions\Exceptions;

use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * Thrown when any criterion passed into repository is invalid.
 */
class BadCriteriaException extends RepositoryException
{
}

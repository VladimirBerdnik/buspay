<?php

namespace App\Domain\Exceptions\Constraint;

use App\Extensions\ContextRetrievingTrait;
use LogicException;

/**
 * Business logic constraint exception. Should be thrown in case of business rule breach attempt.
 */
abstract class BusinessLogicConstraintException extends LogicException
{
    use ContextRetrievingTrait;
}

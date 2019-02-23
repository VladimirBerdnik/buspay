<?php

namespace App\Domain\Import\Exceptions\Constraint;

use App\Domain\Exceptions\Constraint\BusinessLogicConstraintException;

/**
 * Business logic constraint exception. Should be thrown in case of business rule breach attempt during entities import
 * process.
 */
abstract class BusinessLogicConstraintImportException extends BusinessLogicConstraintException
{
}

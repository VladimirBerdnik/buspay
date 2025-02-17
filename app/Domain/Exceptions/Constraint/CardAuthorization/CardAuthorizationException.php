<?php

namespace App\Domain\Exceptions\Constraint\CardAuthorization;

use App\Domain\Exceptions\Integrity\BusinessLogicIntegrityException;

/**
 * Thrown when some error occurred during cards on validator authorization attempt.
 */
abstract class CardAuthorizationException extends BusinessLogicIntegrityException
{
}

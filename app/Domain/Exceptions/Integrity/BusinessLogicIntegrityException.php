<?php

namespace App\Domain\Exceptions\Integrity;

use Exception;

/**
 * Business logic integrity exception. Should be thrown in case of inconsistent application state detection.
 */
abstract class BusinessLogicIntegrityException extends Exception
{
}

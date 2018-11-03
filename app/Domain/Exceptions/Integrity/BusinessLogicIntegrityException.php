<?php

namespace App\Domain\Exceptions\Integrity;

use Exception;

/**
 * Business logic integrity exception. Should be extended by concrete exception for certain business rule breach.
 */
abstract class BusinessLogicIntegrityException extends Exception
{
}

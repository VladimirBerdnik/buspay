<?php

namespace App\Domain\Import\Exceptions;

use App\Domain\Exceptions\Integrity\BusinessLogicIntegrityException;

/**
 * Business logic integrity exception. Should be thrown in case of inconsistent application state detection during
 * import process.
 */
abstract class BusinessLogicIntegrityImportException extends BusinessLogicIntegrityException
{
}

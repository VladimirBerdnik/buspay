<?php

namespace App\Domain\Exceptions\Integrity;

/**
 * Thrown when route sheets inconsistent state found.
 */
class InconsistentRouteSheetStateException extends BusinessLogicIntegrityException
{
    /**
     * Thrown when bus routes inconsistent state found.
     */
    public function __construct()
    {
        parent::__construct('Inconsistent route sheets state detected');
    }
}

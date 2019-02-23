<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\Driver;

/**
 * Thrown when driver cannot be reassigned to another company due to possible payment calculation issues.
 */
class DriverReassignException extends BusinessLogicConstraintException
{
    /**
     * Driver that can't be reassigned.
     *
     * @var Driver
     */
    protected $driver;

    /**
     * Thrown when driver cannot be reassigned to another company due to possible payment calculation issues.
     *
     * @param Driver $driver Driver that can't be reassigned
     */
    public function __construct(Driver $driver)
    {
        parent::__construct('Driver cannot be reassigned to another company');
        $this->driver = $driver;
    }
}

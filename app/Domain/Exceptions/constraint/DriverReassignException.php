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
    private $driver;

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

    /**
     * Returns driver that can't be reassigned.
     *
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }
}

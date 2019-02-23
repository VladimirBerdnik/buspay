<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\Driver;

/**
 * Thrown when driver cannot be deleted due to related records restrictions.
 */
class DriverDeletionException extends BusinessLogicConstraintException
{
    /**
     * Driver that can't be deleted.
     *
     * @var Driver
     */
    protected $driver;

    /**
     * Thrown when driver cannot be deleted due to related records restrictions.
     *
     * @param Driver $driver Driver that can't be deleted
     */
    public function __construct(Driver $driver)
    {
        parent::__construct('Driver with related records cannot be deleted');
        $this->driver = $driver;
    }
}

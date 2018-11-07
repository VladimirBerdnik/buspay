<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\Bus;

/**
 * Thrown when bus cannot be reassigned to another company due to related records restrictions.
 */
class BusReassignException extends BusinessLogicConstraintException
{
    /**
     * Bus that can't be reassigned.
     *
     * @var Bus
     */
    private $bus;

    /**
     * Thrown when bus cannot be reassigned to another company due to related records restrictions.
     *
     * @param Bus $bus Bus that can't be reassigned
     */
    public function __construct(Bus $bus)
    {
        parent::__construct('Bus cannot be reassigned to another company');
        $this->bus = $bus;
    }

    /**
     * Returns bus that can't be reassigned.
     *
     * @return Bus
     */
    public function getBus(): Bus
    {
        return $this->bus;
    }
}

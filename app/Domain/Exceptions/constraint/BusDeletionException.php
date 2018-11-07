<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\Bus;

/**
 * Thrown when bus cannot be deleted due to related records restrictions.
 */
class BusDeletionException extends BusinessLogicConstraintException
{
    /**
     * Bus that can't be deleted.
     *
     * @var Bus
     */
    private $bus;

    /**
     * Thrown when bus cannot be deleted due to related records restrictions.
     *
     * @param Bus $bus Bus that can't be deleted
     */
    public function __construct(Bus $bus)
    {
        parent::__construct('Bus with related records cannot be deleted');
        $this->bus = $bus;
    }

    /**
     * Returns bus that can't be deleted.
     *
     * @return Bus
     */
    public function getBus(): Bus
    {
        return $this->bus;
    }
}

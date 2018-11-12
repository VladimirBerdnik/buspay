<?php

namespace App\Domain\Exceptions\Constraint;

use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Models\DriversCard;

/**
 * Thrown when card to driver assignment already exists.
 */
class DriverCardExistsException extends BusinessLogicConstraintException
{
    /**
     * Card to driver assignment for which activity period exists.
     *
     * @var DriversCard
     */
    private $driversCard;

    /**
     * Thrown when card to driver assignment already exists.
     *
     * @param DriversCard|IActivityPeriod $driversCard Card to driver assignment for which activity period exists
     */
    public function __construct(DriversCard $driversCard)
    {
        parent::__construct('Driver activity period already exists exception');
        $this->driversCard = $driversCard;
    }

    /**
     * Card to driver assignment for which activity period exists.
     *
     * @return DriversCard
     */
    public function getDriversCard(): DriversCard
    {
        return $this->driversCard;
    }
}

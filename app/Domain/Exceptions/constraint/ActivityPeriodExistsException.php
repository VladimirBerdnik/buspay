<?php

namespace App\Domain\Exceptions\Constraint;

use App\Extensions\ActivityPeriod\IActivityPeriod;

/**
 * Thrown when master to parent assignment already exists.
 */
class ActivityPeriodExistsException extends BusinessLogicConstraintException
{
    /**
     * Existing activity period.
     *
     * @var IActivityPeriod
     */
    private $activityPeriod;

    /**
     * Thrown when master to parent assignment already exists.
     *
     * @param IActivityPeriod $activityPeriod Existing activity period
     */
    public function __construct(IActivityPeriod $activityPeriod)
    {
        parent::__construct('Activity period already exists exception');
        $this->activityPeriod = $activityPeriod;
    }

    /**
     * Existing activity period.
     *
     * @return IActivityPeriod
     */
    public function getActivityPeriod(): IActivityPeriod
    {
        return $this->activityPeriod;
    }
}

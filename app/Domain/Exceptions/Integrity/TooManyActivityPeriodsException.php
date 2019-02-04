<?php

namespace App\Domain\Exceptions\Integrity;

use App\Extensions\ActivityPeriod\IActivityPeriod;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple master to related records assignments for date are exists.
 */
class TooManyActivityPeriodsException extends BusinessLogicIntegrityException
{
    /**
     * Date for which many assignments exists.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * Existing activity periods.
     *
     * @var IActivityPeriod|Collection
     */
    protected $activityPeriods;

    /**
     * Thrown when multiple master to related records assignments for date are exists.
     *
     * @param Carbon $date Date for which many assignments exists
     * @param Collection|IActivityPeriod $activityPeriods Existing activity periods
     */
    public function __construct(Carbon $date, Collection $activityPeriods)
    {
        parent::__construct('Few master to related records assignments for date are exists');
        $this->date = $date;
        $this->activityPeriods = $activityPeriods;
    }
}

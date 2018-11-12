<?php

namespace App\Extensions\ActivityPeriod;

use Carbon\Carbon;

/**
 * Activity period helper. Allows to perform activity period related checks.
 *
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 */
trait HasActivityPeriod
{
    /**
     * Checks whether activity period covers passed date or not.
     *
     * @param Carbon $date Date to check
     *
     * @return boolean
     */
    public function activityPeriodCovers(Carbon $date): bool
    {
        return $this->active_from->gte($date) && (!$this->active_to || $this->active_to->lte($date));
    }

    /**
     * Checks whether activity period covers current date or not.
     *
     * @return boolean
     */
    public function activityPeriodActive(): bool
    {
        return $this->activityPeriodCovers(Carbon::now());
    }
}

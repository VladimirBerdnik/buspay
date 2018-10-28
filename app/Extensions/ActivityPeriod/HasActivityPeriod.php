<?php

namespace App\Extensions\ActivityPeriod;

use Carbon\Carbon;

/**
 * Activity period helper. Allows to deactivate period and perform activity period related checks.
 *
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 */
trait HasActivityPeriod
{
    /**
     * Sets end date of activity period.
     *
     * @param Carbon|null $activeTo New end date of activity period
     */
    protected function setActiveTo(?Carbon $activeTo): void
    {
        $this->active_to = $activeTo;
    }

    /**
     * Deactivates record at given date and time.
     *
     * @param Carbon|null $activeTo Deactivate at this period. When not passed current date and time will be used
     */
    public function deactivate(?Carbon $activeTo = null): void
    {
        $this->setActiveTo($activeTo ?? Carbon::now());
    }

    /**
     * Reactivates deactivated period.
     */
    public function reactivate(): void
    {
        $this->setActiveTo(null);
    }

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

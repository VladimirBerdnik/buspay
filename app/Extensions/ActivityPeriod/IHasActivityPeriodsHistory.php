<?php

namespace App\Extensions\ActivityPeriod;

use Illuminate\Support\Collection;

/**
 * Activity periods historical records retrieving helper interface. Used by models that have historical information
 * about their activity periods.
 */
interface IHasActivityPeriodsHistory
{
    /**
     * Returns list of activity periods.
     *
     * @return Collection|IHasActivityPeriod[]
     */
    public function getActivityPeriodsRecords(): Collection;
}

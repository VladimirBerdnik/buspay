<?php

namespace App\Extensions\ActivityPeriod;

use Carbon\Carbon;

/**
 * Activity period helper interface. Allows to perform activity period related checks.
 */
interface IHasActivityPeriod
{
    /**
     * Checks whether activity period covers passed date or not.
     *
     * @param Carbon $date Date to check
     *
     * @return boolean
     */
    public function activityPeriodCovers(Carbon $date): bool;

    /**
     * Checks whether activity period covers current date or not.
     *
     * @return boolean
     */
    public function activityPeriodActive(): bool;

    /**
     * Returns list of attributes involved into activity period. Each of them should be used only once at any moment.
     *
     * @return string[]
     */
    public function getUniquenessAttributes(): array;
}

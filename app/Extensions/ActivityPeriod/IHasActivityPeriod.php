<?php

namespace App\Extensions\ActivityPeriod;

use Carbon\Carbon;

/**
 * Activity period helper interface. Allows to deactivate period and perform activity period related checks.
 */
interface IHasActivityPeriod
{
    /**
     * Deactivates record at given date and time.
     *
     * @param Carbon|null $activeTo Deactivate at this period. When not passed current date and time will be used
     */
    public function deactivate(?Carbon $activeTo = null): void;

    /**
     * Reactivates deactivated period.
     */
    public function reactivate(): void;

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

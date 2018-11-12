<?php

namespace App\Extensions\ActivityPeriod;

use Carbon\Carbon;

/**
 * Activity period record. Allows to perform activity period related checks.
 */
interface IActivityPeriod
{
    /**
     * Returns activation period model unique identifier.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Returns model attributes as array.
     *
     * @return mixed[]|mixed
     */
    public function toArray();

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
     * Returns start date of period activity.
     *
     * @return Carbon
     */
    public function getActiveFrom(): Carbon;

    /**
     * Returns end date of period activity. Returns null when period is not over.
     *
     * @return Carbon|null
     */
    public function getActiveTo(): ?Carbon;

    /**
     * Returns attribute involved into activity period that should be presented only once at one moment of time.
     *
     * @return string
     */
    public function masterModelRelationAttribute(): string;

    /**
     * Returns attribute name that is points to related activity period record model.
     *
     * @return string
     */
    public function relatedModelRelationAttribute(): string;

    /**
     * Returns activity period master record.
     *
     * @return IActivityPeriodMaster
     */
    public function getMasterRecord(): IActivityPeriodMaster;

    /**
     * Returns activity period related record.
     *
     * @return IActivityPeriodRelated
     */
    public function getRelatedRecord(): IActivityPeriodRelated;
}

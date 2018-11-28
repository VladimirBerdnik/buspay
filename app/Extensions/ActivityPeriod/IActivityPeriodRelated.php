<?php

namespace App\Extensions\ActivityPeriod;

/**
 * Activity period related record. This record can be presented in activity periods records multiple times at same
 * moment of time.
 */
interface IActivityPeriodRelated extends IActivityPeriodParticipant
{
    /**
     * Returns activation period related model unique identifier.
     *
     * @return mixed
     */
    public function getKey();
}

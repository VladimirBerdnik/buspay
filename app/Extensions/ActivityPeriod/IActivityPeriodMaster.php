<?php

namespace App\Extensions\ActivityPeriod;

/**
 * Activity period master record. This record should be presented in related activity periods records only once at the
 * same moment of time.
 */
interface IActivityPeriodMaster
{
    /**
     * Returns activation period master model unique identifier.
     *
     * @return mixed
     */
    public function getKey();
}

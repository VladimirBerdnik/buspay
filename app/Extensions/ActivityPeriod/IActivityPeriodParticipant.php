<?php

namespace App\Extensions\ActivityPeriod;

/**
 * Activity period participant record. This record can be master or related model in activity period record.
 */
interface IActivityPeriodParticipant
{
    /**
     * Returns model unique identifier.
     *
     * @return mixed
     */
    public function getKey();
}

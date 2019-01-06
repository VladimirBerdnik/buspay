<?php

namespace App\Policies;

use App\Domain\Enums\Abilities;
use App\Models\Driver;
use App\Models\User;

/**
 * Policies checks for driver models.
 */
class DriverEntityPolicy extends EntityTypePolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * @param User $user User for which need to check ability
     * @param Driver $driver Model for which need to check policy
     *
     * @return boolean
     */
    public function changeDriverBus(User $user, Driver $driver): bool
    {
        return $this->checkAbilityForEntity($user, Abilities::CHANGE_DRIVER_BUS, $driver);
    }
}

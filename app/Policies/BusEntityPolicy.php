<?php

namespace App\Policies;

use App\Domain\Enums\Abilities;
use App\Models\Bus;
use App\Models\User;

/**
 * Policies checks for bus models.
 */
class BusEntityPolicy extends EntityTypePolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * @param User $user User for which need to check ability
     * @param Bus $bus Model for which need to check policy
     *
     * @return boolean
     */
    public function changeBusRoute(User $user, Bus $bus): bool
    {
        return $this->checkAbilityForEntity($user, Abilities::CHANGE_BUS_ROUTE, $bus);
    }
}

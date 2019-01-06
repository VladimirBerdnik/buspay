<?php

namespace App\Policies;

use App\Domain\Enums\Abilities;
use App\Models\Card;
use App\Models\User;

/**
 * Policies checks for card models.
 */
class CardEntityPolicy extends EntityTypePolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * @param User $user User for which need to check ability
     * @param Card $card Model for which need to check policy
     *
     * @return boolean
     */
    public function getDriversCards(User $user, Card $card): bool
    {
        return $this->checkAbilityForEntityClass($user, Abilities::GET_DRIVERS_CARDS, get_class($card));
    }
}

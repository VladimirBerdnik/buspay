<?php

namespace App\Policies;

use App\Domain\Enums\Abilities;
use App\Models\Transaction;
use App\Models\User;

/**
 * Policies checks for transaction models.
 */
class TransactionEntityPolicy extends EntityTypePolicy
{
    /**
     * Determine whether the user can see card details of transaction.
     *
     * @param User $user User for which need to check ability
     * @param Transaction $transaction Model for which need to check policy
     *
     * @return boolean
     */
    public function showTransactionCard(User $user, Transaction $transaction): bool
    {
        return $this->checkAbilityForEntityClass($user, Abilities::SHOW_TRANSACTION_CARD, get_class($transaction));
    }
}

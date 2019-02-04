<?php

namespace App\Domain\Exceptions\Constraint;

use App\Domain\Exceptions\Integrity\BusinessLogicIntegrityException;
use App\Models\Card;

/**
 * Thrown when card attributes cannot be changed during update attempt.
 */
class CardReassignException extends BusinessLogicIntegrityException
{
    /**
     * Card that can't be reassigned.
     *
     * @var Card
     */
    protected $card;

    /**
     * Thrown when card attributes cannot be changed during update attempt.
     *
     * @param Card $card Card that can't be reassigned
     */
    public function __construct(Card $card)
    {
        parent::__construct('Card details cannot be changed');
        $this->card = $card;
    }
}

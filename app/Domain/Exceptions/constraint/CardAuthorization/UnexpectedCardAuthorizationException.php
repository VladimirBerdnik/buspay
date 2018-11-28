<?php

namespace App\Domain\Exceptions\Constraint\CardAuthorization;

use App\Models\Card;

/**
 * Thrown when authorization by unsupported card performed.
 */
class UnexpectedCardAuthorizationException extends CardAuthorizationException
{
    /**
     * Authorized unexpected card.
     *
     * @var Card
     */
    private $card;

    /**
     * Thrown when authorization by unsupported card performed.
     *
     * @param Card $card Authorized unexpected card
     */
    public function __construct(Card $card)
    {
        parent::__construct('Unsupported card authorization');
        $this->card = $card;
    }

    /**
     * Authorized unexpected card.
     *
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }
}

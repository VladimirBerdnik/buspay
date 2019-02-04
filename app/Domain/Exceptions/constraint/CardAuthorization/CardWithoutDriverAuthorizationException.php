<?php

namespace App\Domain\Exceptions\Constraint\CardAuthorization;

use App\Models\Card;
use Carbon\Carbon;

/**
 * Thrown when authorization by card without assigned driver performed.
 */
class CardWithoutDriverAuthorizationException extends CardAuthorizationException
{
    /**
     * Authorized card without driver.
     *
     * @var Card
     */
    protected $card;

    /**
     * Authorization date.
     *
     * @var Carbon
     */
    protected $authorizedAt;

    /**
     * Thrown when authorization by card without assigned driver performed.
     *
     * @param Card $card Authorized card without driver
     * @param Carbon $authorizedAt Authorization date
     */
    public function __construct(Card $card, Carbon $authorizedAt)
    {
        $this->card = $card;
        $this->authorizedAt = $authorizedAt;
        parent::__construct('Unsupported card authorization');
    }
}

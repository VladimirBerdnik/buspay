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
    private $card;

    /**
     * Authorization date.
     *
     * @var Carbon
     */
    private $authorizedAt;

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

    /**
     * Authorized card without driver.
     *
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * Authorization date.
     *
     * @return Carbon
     */
    public function getAuthorizedAt(): Carbon
    {
        return $this->authorizedAt;
    }
}

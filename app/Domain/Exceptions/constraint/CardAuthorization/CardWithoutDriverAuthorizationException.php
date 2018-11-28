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
    private $carbon;

    /**
     * Thrown when authorization by card without assigned driver performed.
     *
     * @param Card $card Authorized card without driver
     * @param Carbon $carbon Authorization date
     */
    public function __construct(Card $card, Carbon $carbon)
    {
        parent::__construct('Unsupported card authorization');
        $this->card = $card;
        $this->carbon = $carbon;
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
    public function getCarbon(): Carbon
    {
        return $this->carbon;
    }
}

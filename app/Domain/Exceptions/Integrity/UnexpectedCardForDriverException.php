<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Card;
use App\Models\DriversCard;

/**
 * Thrown when card to driver assignment has unexpected card value.
 */
class UnexpectedCardForDriverException extends BusinessLogicIntegrityException
{
    /**
     * Card to driver assignment where unexpected card found.
     *
     * @var DriversCard
     */
    protected $driversCard;

    /**
     * Expected card.
     *
     * @var Card
     */
    protected $card;

    /**
     * Thrown when card to driver assignment has unexpected card value.
     *
     * @param DriversCard $driversCard Card to driver assignment where unexpected card found
     * @param Card $card Expected card
     */
    public function __construct(DriversCard $driversCard, Card $card)
    {
        parent::__construct('Unexpected card in card to driver historical assignment');
        $this->driversCard = $driversCard;
        $this->card = $card;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $driversCard = $this->driversCard;

        return "Unexpected card [{$driversCard->card_id}] for driver [{$driversCard->driver_id}] found " .
            "in card to driver assignment [{$driversCard->id}]. Expected card is [{$this->card->id}]";
    }
}

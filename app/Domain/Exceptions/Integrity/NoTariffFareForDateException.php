<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\CardType;
use App\Models\Tariff;
use Carbon\Carbon;

/**
 * Thrown when no tariff fare for date, tariff and card type exist.
 */
class NoTariffFareForDateException extends BusinessLogicIntegrityException
{
    /**
     * Date for which tariff fare missed.
     *
     * @var Carbon
     */
    private $date;

    /**
     * Card type for which tariff fare missed.
     *
     * @var CardType
     */
    private $cardType;

    /**
     * Tariff for which tariff fare missed.
     *
     * @var Tariff
     */
    private $tariff;

    /**
     * Thrown when no tariff fare for date, tariff and card type exist.
     *
     * @param CardType $cardType Card type for which tariff fare missed
     * @param Tariff $tariff Tariff for which tariff fare missed
     * @param Carbon $date Date for which tariff fare missed
     */
    public function __construct(CardType $cardType, Tariff $tariff, Carbon $date)
    {
        parent::__construct('No tariff fare for date');
        $this->date = $date;
        $this->cardType = $cardType;
        $this->tariff = $tariff;
    }

    /**
     * Returns Date for which tariff fare missed.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Card type for which tariff fare missed.
     *
     * @return CardType
     */
    public function getCardType(): CardType
    {
        return $this->cardType;
    }

    /**
     * Tariff for which tariff fare missed.
     *
     * @return Tariff
     */
    public function getTariff(): Tariff
    {
        return $this->tariff;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "No tariff fare for date {$this->getDate()->toIso8601String()} " .
            "for tariff [{$this->getTariff()->getKey()} " .
            "for card type [{$this->getCardType()->getKey()}]";
    }
}

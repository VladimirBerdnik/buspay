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
    protected $date;

    /**
     * Card type for which tariff fare missed.
     *
     * @var CardType
     */
    protected $cardType;

    /**
     * Tariff for which tariff fare missed.
     *
     * @var Tariff
     */
    protected $tariff;

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
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "No tariff fare for date {$this->date->toIso8601String()} " .
            "for tariff [{$this->tariff->getKey()} " .
            "for card type [{$this->cardType->getKey()}]";
    }
}

<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\CardType;
use App\Models\Tariff;
use App\Models\TariffFare;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when too many tariff fare for date, tariff and card type exist.
 */
class TooManyTariffFaresForDateException extends BusinessLogicIntegrityException
{
    /**
     * Date for which too many tariff fares exists.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * Card type for which too many tariff fares exists.
     *
     * @var CardType
     */
    protected $cardType;

    /**
     * Tariff for which too many tariff fares exists.
     *
     * @var Tariff
     */
    protected $tariff;

    /**
     * Existing tariff fares.
     *
     * @var Collection|TariffFare[]
     */
    protected $tariffFares;

    /**
     * Thrown when too many tariff fare for date, tariff and card type exist.
     *
     * @param CardType $cardType Card type for which too many tariff fares exists
     * @param Tariff $tariff Tariff for which too many tariff fares exists
     * @param Carbon $date Date for which too many tariff fares exists
     * @param Collection|TariffFare[] $tariffFares Existing tariff fares
     */
    public function __construct(CardType $cardType, Tariff $tariff, Carbon $date, Collection $tariffFares)
    {
        parent::__construct('Too many tariff fares for date');
        $this->date = $date;
        $this->cardType = $cardType;
        $this->tariff = $tariff;
        $this->tariffFares = $tariffFares;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $tariffFaresIdentifiers = $this->tariffFares->pluck(TariffFare::ID)->toArray();

        return "Too many tariff fares for date {$this->date->toIso8601String()} " .
            "for tariff [{$this->tariff->getKey()} " .
            "for card type [{$this->cardType->getKey()}] exists" . implode(', ', $tariffFaresIdentifiers);
    }
}

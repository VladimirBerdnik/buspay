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
    private $date;

    /**
     * Card type for which too many tariff fares exists.
     *
     * @var CardType
     */
    private $cardType;

    /**
     * Tariff for which too many tariff fares exists.
     *
     * @var Tariff
     */
    private $tariff;

    /**
     * Existing tariff fares.
     *
     * @var Collection|TariffFare[]
     */
    private $tariffFares;

    /**
     * Existing tariff fares.
     *
     * @return TariffFare[]|Collection
     */
    private function getTariffFares(): Collection
    {
        return $this->tariffFares;
    }

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
     * Returns Date for which too many tariff fares exists.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Card type for which too many tariff fares exists.
     *
     * @return CardType
     */
    public function getCardType(): CardType
    {
        return $this->cardType;
    }

    /**
     * Tariff for which too many tariff fares exists.
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
        $tariffFaresIdentifiers = $this->getTariffFares()->pluck(TariffFare::ID)->toArray();

        return "Too many tariff fares for date {$this->getDate()->toIso8601String()} " .
            "for tariff [{$this->getTariff()->getKey()} " .
            "for card type [{$this->getCardType()->getKey()}] exists" . implode(', ', $tariffFaresIdentifiers);
    }
}

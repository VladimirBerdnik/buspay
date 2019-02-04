<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Card;
use App\Models\DriversCard;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple drivers to card assignments for date are exists.
 */
class TooManyCardDriversException extends BusinessLogicIntegrityException
{
    /**
     * Date for which many drivers to card assignments exists.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * List of drivers to card assignments for date.
     *
     * @var Collection|DriversCard[]
     */
    protected $cardDrivers;

    /**
     * Card for which few assignments exists.
     *
     * @var Card
     */
    protected $card;

    /**
     * Thrown when multiple drivers to card assignments for date are exists.
     *
     * @param Carbon $date Date for which many drivers to card assignments exists
     * @param Card $card Card for which few assignments exists
     * @param Collection|DriversCard[] $cardDrivers List of drivers to card assignments for date
     */
    public function __construct(Carbon $date, Card $card, Collection $cardDrivers)
    {
        parent::__construct('Few drivers to card assignments for date');
        $this->date = $date;
        $this->cardDrivers = $cardDrivers;
        $this->card = $card;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->cardDrivers->pluck(Card::ID)->toArray();
        $date = $this->date->toIso8601String();

        return "For date {$date} few drivers to card [{$this->card->id}] " .
            "assignments exists: " . implode(', ', $periodsIdentifiers);
    }
}

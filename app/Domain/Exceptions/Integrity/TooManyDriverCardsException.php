<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Driver;
use App\Models\DriversCard;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple card to driver assignments for date are exists.
 */
class TooManyDriverCardsException extends BusinessLogicIntegrityException
{
    /**
     * Date for which many card to driver assignments exists.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * List of card to driver assignments for date.
     *
     * @var Collection|DriversCard[]
     */
    protected $cardDrivers;

    /**
     * Driver for which few assignments exists.
     *
     * @var Driver
     */
    protected $driver;

    /**
     * Thrown when multiple card to driver assignments for date are exists.
     *
     * @param Carbon $date Date for which many card to driver assignments exists
     * @param Driver $card Driver for which few assignments exists
     * @param Collection|DriversCard[] $cardDrivers List of card to driver assignments for date
     */
    public function __construct(Carbon $date, Driver $card, Collection $cardDrivers)
    {
        parent::__construct('Few cards to driver assignments for date');
        $this->date = $date;
        $this->cardDrivers = $cardDrivers;
        $this->driver = $card;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->cardDrivers->pluck(Driver::ID)->toArray();
        $date = $this->date->toIso8601String();

        return "For date {$date} few cards to driver [{$this->driver->id}] " .
            "assignments exists: " . implode(', ', $periodsIdentifiers);
    }
}

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
    private $date;

    /**
     * List of card to driver assignments for date.
     *
     * @var Collection|DriversCard[]
     */
    private $cardDrivers;

    /**
     * Driver for which few assignments exists.
     *
     * @var Driver
     */
    private $driver;

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
     * Returns Date for which many card to driver assignments exists.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Driver for which few assignments exists.
     *
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * Returns List of card to driver assignments for date.
     *
     * @return Collection|DriversCard[]
     */
    public function getCardDrivers()
    {
        return $this->cardDrivers;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->getCardDrivers()->pluck(Driver::ID)->toArray();
        $date = $this->getDate()->toIso8601String();

        return "For date {$date} few cards to driver [{$this->getDriver()->id}] " .
            "assignments exists: " . implode(', ', $periodsIdentifiers);
    }
}

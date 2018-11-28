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
    private $date;

    /**
     * List of drivers to card assignments for date.
     *
     * @var Collection|DriversCard[]
     */
    private $cardDrivers;

    /**
     * Card for which few assignments exists.
     *
     * @var Card
     */
    private $card;

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
     * Returns Date for which many drivers to card assignments exists.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Card for which few assignments exists.
     *
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * Returns List of drivers to card assignments for date.
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
        $periodsIdentifiers = $this->getCardDrivers()->pluck(Card::ID)->toArray();
        $date = $this->getDate()->toIso8601String();

        return "For date {$date} few drivers to card [{$this->getCard()->id}] " .
            "assignments exists: " . implode(', ', $periodsIdentifiers);
    }
}

<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Driver;
use App\Models\RouteSheet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple route sheets for driver at date are exists.
 */
class TooManyDriverRouteSheetsForDateException extends TooManyRouteSheetsForDateException
{
    /**
     * Driver for which few route sheets for date exists.
     *
     * @var Driver
     */
    protected $driver;

    /**
     * Thrown when multiple route sheets for date are exists.
     *
     * @param Carbon $date Date for which many route sheets exists
     * @param Driver $card Driver for which few route sheets for date exists
     * @param Collection|RouteSheet[] $routeSheets List of route sheets for date
     */
    public function __construct(Carbon $date, Driver $card, Collection $routeSheets)
    {
        parent::__construct($date, $routeSheets);
        $this->driver = $card;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->routeSheets->pluck(RouteSheet::ID)->toArray();
        $date = $this->date->toIso8601String();

        return "For date {$date} few route sheets of driver [{$this->driver->id}] exists: " .
            implode(', ', $periodsIdentifiers);
    }
}

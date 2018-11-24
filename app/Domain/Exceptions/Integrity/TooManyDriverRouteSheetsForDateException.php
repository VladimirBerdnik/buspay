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
    private $driver;

    /**
     * Thrown when multiple route sheets for date are exists.
     *
     * @param Carbon $date Date for which many route sheets exists
     * @param Driver $driver Driver for which few route sheets for date exists
     * @param Collection|RouteSheet[] $routeSheets List of route sheets for date
     */
    public function __construct(Carbon $date, Driver $driver, Collection $routeSheets)
    {
        parent::__construct($date, $routeSheets);
        $this->driver = $driver;
    }

    /**
     * Driver for which few route sheets for date exists.
     *
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->getRouteSheets()->pluck(RouteSheet::ID)->toArray();
        $date = $this->getDate()->toIso8601String();

        return "For date {$date} few route sheets of driver [{$this->getDriver()->id}] exists: " .
            implode(', ', $periodsIdentifiers);
    }
}

<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Bus;
use App\Models\RouteSheet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple route sheets for bus at date are exists.
 */
class TooManyBusRouteSheetsForDateException extends TooManyRouteSheetsForDateException
{
    /**
     * Bus for which few route sheets for date exists.
     *
     * @var Bus
     */
    private $bus;

    /**
     * Thrown when multiple route sheets for date are exists.
     *
     * @param Carbon $date Date for which many route sheets exists
     * @param Bus $bus Bus for which few route sheets for date exists
     * @param Collection|RouteSheet[] $routeSheets List of route sheets for date
     */
    public function __construct(Carbon $date, Bus $bus, Collection $routeSheets)
    {
        parent::__construct($date, $routeSheets);
        $this->bus = $bus;
    }

    /**
     * Bus for which few route sheets for date exists.
     *
     * @return Bus
     */
    public function getBus(): Bus
    {
        return $this->bus;
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

        return "For date {$date} few route sheets of bus [{$this->getBus()->id}] exists: " .
            implode(', ', $periodsIdentifiers);
    }
}

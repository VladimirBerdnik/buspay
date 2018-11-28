<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\RouteSheet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple route sheets for date are exists.
 */
abstract class TooManyRouteSheetsForDateException extends BusinessLogicIntegrityException
{
    /**
     * Date for which many route sheets exists.
     *
     * @var Carbon
     */
    private $date;

    /**
     * List of route sheets for date.
     *
     * @var Collection|RouteSheet[]
     */
    private $routeSheets;

    /**
     * Thrown when multiple route sheets for date are exists.
     *
     * @param Carbon $date Date for which many route sheets exists
     * @param Collection|RouteSheet[] $routeSheets List of route sheets for date
     */
    public function __construct(Carbon $date, Collection $routeSheets)
    {
        parent::__construct('Few route sheets for date');
        $this->date = $date;
        $this->routeSheets = $routeSheets;
    }

    /**
     * Returns Date for which many route sheets exists.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Returns List of route sheets for date.
     *
     * @return RouteSheet[]|Collection
     */
    public function getRouteSheets()
    {
        return $this->routeSheets;
    }
}

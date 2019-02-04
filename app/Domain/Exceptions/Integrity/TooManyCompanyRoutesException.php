<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\CompaniesRoute;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple company to route assignments for date are exists.
 */
class TooManyCompanyRoutesException extends BusinessLogicIntegrityException
{
    /**
     * Date for which many company to route assignments exists.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * List of company to route assignments for date.
     *
     * @var Collection|CompaniesRoute[]
     */
    protected $companyRoutes;

    /**
     * Route for which few assignments exists.
     *
     * @var Route
     */
    protected $route;

    /**
     * Thrown when multiple company to route assignments for date are exists.
     *
     * @param Carbon $date Date for which many company to route assignments exists
     * @param Route $route Route for which few assignments exists
     * @param Collection|CompaniesRoute[] $companyRoutes List of company to route assignments for date
     */
    public function __construct(Carbon $date, Route $route, Collection $companyRoutes)
    {
        parent::__construct('Few company to route assignments for date');
        $this->date = $date;
        $this->companyRoutes = $companyRoutes;
        $this->route = $route;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->companyRoutes->pluck(CompaniesRoute::ID)->toArray();
        $date = $this->date->toIso8601String();

        return "For date {$date} few companies to route [{$this->route->id}] " .
            "assignments exists: " . implode(', ', $periodsIdentifiers);
    }
}

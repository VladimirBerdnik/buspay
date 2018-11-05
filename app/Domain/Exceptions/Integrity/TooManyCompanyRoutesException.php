<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\CompaniesRoute;
use App\Models\Company;
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
    private $date;

    /**
     * List of company to route assignments for date.
     *
     * @var Collection|CompaniesRoute[]
     */
    private $companyRoutes;

    /**
     * Company to which few assignments exists.
     *
     * @var Company
     */
    private $company;

    /**
     * Route for which few assignments exists.
     *
     * @var Route
     */
    private $route;

    /**
     * Thrown when multiple company to route assignments for date are exists.
     *
     * @param Carbon $date Date for which many company to route assignments exists
     * @param Company $company Company to which few assignments exists
     * @param Route $route Route for which few assignments exists
     * @param Collection|CompaniesRoute[] $companyRoutes List of company to route assignments for date
     */
    public function __construct(Carbon $date, Company $company, Route $route, Collection $companyRoutes)
    {
        parent::__construct('Few company to route assignments for date');
        $this->date = $date;
        $this->companyRoutes = $companyRoutes;
        $this->company = $company;
        $this->route = $route;
    }

    /**
     * Returns Date for which many company to route assignments exists.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Company to which few assignments exists.
     *
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * Route for which few assignments exists.
     *
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * Returns List of company to route assignments for date.
     *
     * @return Collection|CompaniesRoute[]
     */
    public function getCompanyRoutes()
    {
        return $this->companyRoutes;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->getCompanyRoutes()->pluck(CompaniesRoute::ID)->toArray();
        $date = $this->getDate()->toIso8601String();

        return "For date {$date} few company [{$this->getCompany()->id}] to route [{$this->getRoute()->id}] " .
            "assignments exists: " . implode(', ', $periodsIdentifiers);
    }
}

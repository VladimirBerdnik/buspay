<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Exceptions\Constraint\ActivityPeriodExistsException;
use App\Domain\Exceptions\Constraint\CompanyRouteExistsException;
use App\Domain\Exceptions\Integrity\TooManyActivityPeriodsException;
use App\Domain\Exceptions\Integrity\TooManyCompanyRoutesException;
use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Models\CompaniesRoute;
use App\Models\Company;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * Companies to routes assignments business-logic service.
 */
class CompaniesRouteService extends ModelRelationActivityPeriodService
{
    /**
     * Opens new company to route assignment period.
     *
     * @param Route $route Route to assign company to
     * @param Company $company Company to assign route to
     * @param Carbon|null $activeFrom Start date of company to route assignment period
     *
     * @return CompaniesRoute|IActivityPeriod
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws TooManyCompanyRoutesException
     */
    public function openCompanyRoutePeriod(Route $route, Company $company, ?Carbon $activeFrom = null): CompaniesRoute
    {
        try {
            return $this->openPeriod($route, $company, $activeFrom);
        } catch (TooManyActivityPeriodsException $e) {
            throw new TooManyCompanyRoutesException($e->getDate(), $route, $e->getActivityPeriods());
        } catch (ActivityPeriodExistsException $e) {
            throw new CompanyRouteExistsException($e->getActivityPeriod());
        }
    }

    /**
     * Closes route to company assignment period.
     *
     * @param CompaniesRoute $companiesRoute Company to route assignment period to close
     * @param Carbon|null $activeTo Date of period at which period should be closed
     *
     * @return CompaniesRoute|IActivityPeriod
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function closeCompanyRoutePeriod(CompaniesRoute $companiesRoute, ?Carbon $activeTo = null): CompaniesRoute
    {
        return $this->closePeriod($companiesRoute, $activeTo);
    }

    /**
     * Returns company to route assignment that was active at passed date.
     *
     * @param Route $route Route to retrieve assignment for
     * @param Carbon|null $date Date to find tariff period
     *
     * @return CompaniesRoute|IActivityPeriod|null
     *
     * @throws TooManyCompanyRoutesException
     */
    public function getForRoute(Route $route, ?Carbon $date = null): ?CompaniesRoute
    {
        try {
            return $this->getPeriodFor($route, $date);
        } catch (TooManyActivityPeriodsException $e) {
            throw new TooManyCompanyRoutesException($date, $route, $e->getActivityPeriods());
        }
    }
}

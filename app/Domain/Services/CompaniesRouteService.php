<?php

namespace App\Domain\Services;

use App\Domain\Dto\CompaniesRouteData;
use App\Domain\Exceptions\Constraint\CompanyRouteExistsException;
use App\Domain\Exceptions\Integrity\TooManyCompanyRoutesException;
use App\Extensions\EntityService;
use App\Models\CompaniesRoute;
use App\Models\Company;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\DateRuleSet;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\Laravel\Validation\RuleSet;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Validator;

/**
 * Companies to routes assignments business-logic service.
 */
class CompaniesRouteService extends EntityService
{
    /**
     * Returns validation rule to store or update company to route assignment.
     *
     * @param CompaniesRoute $companiesRoute Company to route assignment to build rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getRouteValidationRules(CompaniesRoute $companiesRoute): array
    {
        return [
            CompaniesRoute::ACTIVE_FROM => Rule::required()->date()
                ->when($companiesRoute->active_to, function (RuleSet $rule) {
                    /**
                     * Date rules set.
                     *
                     * @var DateRuleSet $rule
                     */
                    return $rule->before(CompaniesRoute::ACTIVE_TO);
                }),
            CompaniesRoute::ACTIVE_TO => Rule::nullable()->date()
                ->when($companiesRoute->active_to, function (RuleSet $rule) {
                    /**
                     * Date rules set.
                     *
                     * @var DateRuleSet $rule
                     */
                    return $rule->after(CompaniesRoute::ACTIVE_FROM);
                }),
            CompaniesRoute::COMPANY_ID => Rule::required()->exists('companies', Company::ID),
            CompaniesRoute::ROUTE_ID => Rule::required()->exists('routes', Route::ID),
        ];
    }

    /**
     * Opens new company to route assignment period.
     *
     * @param Company $company Company to assign route to
     * @param Route $route Route to assign company to
     * @param Carbon|null $activeFrom Start date of company to route assignment period
     *
     * @return CompaniesRoute
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws TooManyCompanyRoutesException
     */
    public function openPeriod(Company $company, Route $route, ?Carbon $activeFrom = null): CompaniesRoute
    {
        Log::debug("Create company [{$company->id}] to route [{$route->id}] assign attempt");

        $activeFrom = $activeFrom ?? Carbon::now();

        $companyRouteData = new CompaniesRouteData([
            CompaniesRouteData::ACTIVE_FROM => $activeFrom,
            CompaniesRouteData::COMPANY_ID => $route->company_id,
            CompaniesRouteData::ROUTE_ID => $route->id,
        ]);

        $companiesRoute = new CompaniesRoute($companyRouteData->toArray());

        Validator::validate($companyRouteData->toArray(), $this->getRouteValidationRules($companiesRoute));

        $companyRoute = $this->getForRoute($route, $activeFrom);

        if ($companyRoute) {
            throw new CompanyRouteExistsException($companyRoute);
        }

        $this->getRepository()->create($companiesRoute);

        Log::debug("Company to route assignment [{$companiesRoute->id}] created");

        return $companiesRoute;
    }

    /**
     * Closes route to company assignment period.
     *
     * @param CompaniesRoute $companiesRoute Company to route assignment period to close
     * @param Carbon|null $activeTo Date of period at which period should be closed
     *
     * @return CompaniesRoute
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function closePeriod(CompaniesRoute $companiesRoute, ?Carbon $activeTo = null): CompaniesRoute
    {
        Log::debug("Close company to route assignment period [{$companiesRoute->id}] attempt");

        $companiesRoute->active_to = $activeTo ?? Carbon::now();

        Validator::validate($companiesRoute->toArray(), $this->getRouteValidationRules($companiesRoute));

        $this->getRepository()->save($companiesRoute);

        Log::debug("Company to route assignment [{$companiesRoute->id}] period closed");

        return $companiesRoute;
    }

    /**
     * Returns company to route assignment that was active at passed date.
     *
     * @param Route $route Route to retrieve assignment for
     * @param Carbon|null $date Date to find tariff period
     *
     * @return CompaniesRoute|null
     *
     * @throws TooManyCompanyRoutesException
     */
    public function getForRoute(Route $route, ?Carbon $date = null): ?CompaniesRoute
    {
        $date = $date ?? Carbon::now();

        $companyRoutes = $this->getRepository()->getWith(
            [],
            [],
            [
                [CompaniesRoute::ROUTE_ID, $route->getKey()],
                [CompaniesRoute::ACTIVE_FROM, '<=', $date],
                [
                    [
                        [CompaniesRoute::ACTIVE_TO, '=', null, 'or'],
                        [CompaniesRoute::ACTIVE_TO, '>=', $date, 'or'],
                    ],
                ],
            ]
        );

        if ($companyRoutes->count() > 1) {
            throw new TooManyCompanyRoutesException($date, $route, $companyRoutes);
        }

        if ($companyRoutes->count() === 1) {
            return $companyRoutes->first();
        }

        return null;
    }
}

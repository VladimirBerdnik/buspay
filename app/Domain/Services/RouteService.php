<?php

namespace App\Domain\Services;

use App\Domain\Dto\RouteData;
use App\Domain\Exceptions\Constraint\RouteDeletionException;
use App\Domain\Exceptions\Constraint\RouteReassignException;
use App\Domain\Exceptions\Integrity\NoCompanyForRouteException;
use App\Domain\Exceptions\Integrity\TooManyCompanyRoutesException;
use App\Domain\Exceptions\Integrity\UnexpectedCompanyForRouteException;
use App\Extensions\EntityService;
use App\Models\Company;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\Contracts\IRepository;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Throwable;
use Validator;

/**
 * Route business-logic service.
 */
class RouteService extends EntityService
{
    /**
     * Company to route assignments business-logic.
     *
     * @var CompaniesRouteService
     */
    private $companiesRouteService;

    /**
     * Route business-logic service.
     *
     * @param ConnectionInterface $connection Data storage connection
     * @param IRepository $repository Handled entities storage
     * @param CompaniesRouteService $companiesRouteService Company to route assignments business-logic
     */
    public function __construct(
        ConnectionInterface $connection,
        IRepository $repository,
        CompaniesRouteService $companiesRouteService
    ) {
        parent::__construct($connection, $repository);
        $this->companiesRouteService = $companiesRouteService;
    }

    /**
     * Returns validation rule to store or update route.
     *
     * @param Route $route Route to build rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getRouteValidationRules(Route $route): array
    {
        return [
            Route::COMPANY_ID => Rule::nullable()->exists('companies', Company::ID),
            Route::NAME => Rule::required()
                // Route name should be unique
                ->unique('routes', Route::NAME, function (Unique $rule) use ($route) {
                    if ($route->exists) {
                        $rule->whereNot(Route::ID, $route->id);
                    }

                    return $rule->whereNull(Route::DELETED_AT);
                }),
        ];
    }

    /**
     * Stores new route.
     *
     * @param RouteData $routeData Route details to create
     *
     * @return Route
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(RouteData $routeData): Route
    {
        Log::debug("Create route with name [{$routeData->name}] attempt");

        $route = new Route($routeData->toArray());

        Validator::validate($routeData->toArray(), $this->getRouteValidationRules($route));

        $this->handleTransaction(function () use ($route): void {
            $this->getRepository()->create($route);

            if ($route->company_id) {
                $this->companiesRouteService->openCompanyRoutePeriod($route, $route->company);
            }
        });

        Log::debug("Route [{$route->id}] created");

        return $route;
    }

    /**
     * Updates route details.
     *
     * @param Route $route Route to update
     * @param RouteData $routeData Route new details
     *
     * @return Route
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(Route $route, RouteData $routeData): Route
    {
        Log::debug("Update route [{$route->id}] attempt");

        $companyChanged = $route->company_id !== $routeData->company_id;
        $previouslyAssignedCompany = $route->company;
        $assignedCompanyId = $routeData->company_id;

        $route->fill($routeData->toArray());

        Validator::validate($routeData->toArray(), $this->getRouteValidationRules($route));

        if ($route->buses->isNotEmpty() && $companyChanged) {
            throw new RouteReassignException($route);
        }

        $this->handleTransaction(function () use (
            $previouslyAssignedCompany,
            $assignedCompanyId,
            $routeData,
            $companyChanged,
            $route
        ): void {
            $date = Carbon::now();

            if ($previouslyAssignedCompany && $companyChanged) {
                $this->closeCurrentRoutePeriod($route, $previouslyAssignedCompany, $date);
            }

            $this->getRepository()->save($route);
            $route->load('company');

            if ($companyChanged && $assignedCompanyId) {
                $this->companiesRouteService->openCompanyRoutePeriod(
                    $route,
                    $route->company,
                    $date->copy()->addSecond()
                );
            }
        });

        Log::debug("Route [{$route->id}] updated");

        return $route;
    }

    /**
     * Deletes route.
     *
     * @param Route $route Route to delete
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function destroy(Route $route): void
    {
        Log::debug("Delete route [{$route->id}] attempt");

        if ($route->buses->isNotEmpty()) {
            throw new RouteDeletionException($route);
        }

        $this->handleTransaction(function () use ($route): void {
            if ($route->company_id) {
                $this->closeCurrentRoutePeriod($route, $route->company);
            }

            $this->getRepository()->delete($route);
        });

        Log::debug("Route [{$route->id}] deleted");
    }

    /**
     * Closes current company to route assignment period.
     *
     * @param Route $route Route for which need to close current company assignment record
     * @param Company $expectedCompany Expected company for route activity period
     * @param Carbon|null $date Date of end of company with route activity period record
     *
     * @throws NoCompanyForRouteException
     * @throws RepositoryException
     * @throws TooManyCompanyRoutesException
     * @throws UnexpectedCompanyForRouteException
     * @throws ValidationException
     */
    private function closeCurrentRoutePeriod(Route $route, Company $expectedCompany, ?Carbon $date = null): void
    {
        $companyRoute = $this->companiesRouteService->getForRoute($route, $date);

        if (!$companyRoute) {
            throw new NoCompanyForRouteException($route);
        }

        if ($companyRoute->company_id !== $expectedCompany->id) {
            throw new UnexpectedCompanyForRouteException($companyRoute, $expectedCompany);
        }

        $this->companiesRouteService->closeCompanyRoutePeriod($companyRoute, $date);
    }
}

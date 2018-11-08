<?php

namespace App\Domain\Services;

use App\Domain\Dto\RouteData;
use App\Domain\Exceptions\Constraint\RouteDeletionException;
use App\Domain\Exceptions\Constraint\RouteReassignException;
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
                $this->companiesRouteService->openPeriod($route->company, $route);
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
     * @throws TooManyCompanyRoutesException
     * @throws Throwable
     */
    public function update(Route $route, RouteData $routeData): Route
    {
        Log::debug("Update route [{$route->id}] attempt");

        Validator::validate($routeData->toArray(), $this->getRouteValidationRules($route));

        $companyChanged = $route->company_id !== $routeData->company_id;
        $companyWasAssigned = $route->company_id;
        $companyAssigned = $routeData->company_id;

        if ($route->buses->isNotEmpty() && $companyChanged) {
            Log::debug("Route [{$route->id}] has related records. Can't reassign");

            throw new RouteReassignException($route);
        }

        $this->handleTransaction(function () use (
            $companyWasAssigned,
            $companyAssigned,
            $routeData,
            $companyChanged,
            $route
        ): void {
            $date = Carbon::now();

            if ($companyWasAssigned && $companyChanged) {
                // Close period for old company
                $companyRoute = $this->companiesRouteService->getForRoute($route, $date);
                if ($companyRoute->company_id !== $route->company_id) {
                    throw new UnexpectedCompanyForRouteException($companyRoute, $route->company);
                }
                $this->companiesRouteService->closePeriod($companyRoute, $date);
            }

            $newAttributes = $routeData->toArray();
            $route->fill($newAttributes);
            $this->getRepository()->save($route);

            if ($companyChanged && $companyAssigned) {
                // Open period for new company
                $this->companiesRouteService->openPeriod($route->company, $route, $date->copy()->addSecond());
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
     * @throws TooManyCompanyRoutesException
     * @throws ValidationException
     * @throws Throwable
     */
    public function destroy(Route $route): void
    {
        Log::debug("Delete route [{$route->id}] attempt");

        if ($route->buses->isNotEmpty()) {
            Log::debug("Route [{$route->id}] has related records. Can't delete");

            throw new RouteDeletionException($route);
        }

        $this->handleTransaction(function () use ($route): void {
            if ($route->company_id) {
                $companyRoute = $this->companiesRouteService->getForRoute($route);
                if ($companyRoute->company_id !== $route->company_id) {
                    throw new UnexpectedCompanyForRouteException($companyRoute, $route->company);
                }
                $this->companiesRouteService->closePeriod($companyRoute);
            }

            $this->getRepository()->delete($route);
        });

        Log::debug("Route [{$route->id}] deleted");
    }
}

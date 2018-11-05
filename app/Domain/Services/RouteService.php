<?php

namespace App\Domain\Services;

use App\Domain\Dto\RouteData;
use App\Domain\Exceptions\Constraint\RouteDeletionException;
use App\Domain\Exceptions\Constraint\RouteReassignException;
use App\Extensions\EntityService;
use App\Models\Company;
use App\Models\Route;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Validator;

/**
 * Route business-logic service.
 */
class RouteService extends EntityService
{
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
            Route::NAME => Rule::unique('routes', Route::NAME, function (Unique $rule) use ($route) {
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
     */
    public function store(RouteData $routeData): Route
    {
        Log::debug("Create route with name [{$routeData->name}] attempt");

        $route = new Route($routeData->toArray());

        Validator::validate($routeData->toArray(), $this->getRouteValidationRules($route));

        $this->getRepository()->create($route);

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
     */
    public function update(Route $route, RouteData $routeData): Route
    {
        Log::debug("Update route [{$route->id}] attempt");

        Validator::validate($routeData->toArray(), $this->getRouteValidationRules($route));

        $newAttributes = $routeData->toArray();

        if ($route->buses->isNotEmpty() && $route->company_id !== $routeData->company_id) {
            Log::debug("Route [{$route->id}] has related records. Can't reassign");

            throw new RouteReassignException($route);
        }

        $route->fill($newAttributes);

        $this->getRepository()->save($route);

        Log::debug("Route [{$route->id}] updated");

        return $route;
    }

    /**
     * Deletes route.
     *
     * @param Route $route Route to delete
     *
     * @throws RepositoryException
     */
    public function destroy(Route $route): void
    {
        Log::debug("Delete route [{$route->id}] attempt");

        if ($route->buses->isNotEmpty()) {
            Log::debug("Route [{$route->id}] has related records. Can't delete");

            throw new RouteDeletionException($route);
        }

        $this->getRepository()->delete($route);

        Log::debug("Route [{$route->id}] deleted");
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\RouteEntityService;
use App\Domain\Enums\Abilities;
use App\Http\Requests\Api\SaveRouteRequest;
use App\Models\Route;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;
use Throwable;

/**
 * Routes requests API controller.
 */
class RoutesApiController extends BaseApiController
{
    /**
     * Routes entity service.
     *
     * @var RouteEntityService
     */
    private $routeService;

    /**
     * Routes requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param RouteEntityService $routeService Routes entity service
     */
    public function __construct(IDataTransformer $transformer, RouteEntityService $routeService)
    {
        parent::__construct($transformer);
        $this->routeService = $routeService;
    }

    /**
     * Returns routes list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize(Abilities::GET, new Route());

        return $this->response->collection(
            $this->routeService->getWith(
                ['company'],
                ['buses'],
                $this->singleCompanyUser()
                    ? [Route::COMPANY_ID => $this->user->company_id]
                    : [],
                new SortOptions(Route::NAME)
            ),
            $this->transformer
        );
    }

    /**
     * Stores new route in application.
     *
     * @param SaveRouteRequest $request Request with new route information
     *
     * @return Response
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(SaveRouteRequest $request): Response
    {
        $this->authorize(Abilities::CREATE, new Route());

        $route = $this->routeService->store($request->getRouteData());

        return $this->response->item($route, $this->transformer);
    }

    /**
     * Updates route details.
     *
     * @param SaveRouteRequest $request Request with new route information
     * @param Route $route Route to update
     *
     * @return Response
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(SaveRouteRequest $request, Route $route): Response
    {
        $this->authorize(Abilities::UPDATE, $route);

        $this->routeService->update($route, $request->getRouteData());

        return $this->response->item($route, $this->transformer);
    }

    /**
     * Removes route from application.
     *
     * @param Route $route Route to delete
     *
     * @return Response
     *
     * @throws Throwable
     */
    public function destroy(Route $route): Response
    {
        $this->authorize(Abilities::DELETE, $route);

        $this->routeService->destroy($route);

        return $this->response->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Services\RouteService;
use App\Http\Requests\Api\SaveRouteRequest;
use App\Models\Route;
use Dingo\Api\Http\Response;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Saritasa\Transformers\IDataTransformer;

/**
 * Routes requests API controller.
 */
class RoutesApiController extends BaseApiController
{
    /**
     * Routes business-logic service.
     *
     * @var RouteService
     */
    private $routeService;

    /**
     * Routes requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param RouteService $routeService Routes business logic service
     */
    public function __construct(IDataTransformer $transformer, RouteService $routeService)
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
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->routeService->getWith(['company'], ['buses'], [], new SortOptions(Route::NAME)),
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
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function store(SaveRouteRequest $request): Response
    {
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
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function update(SaveRouteRequest $request, Route $route): Response
    {
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
     * @throws RepositoryException
     */
    public function destroy(Route $route): Response
    {
        $this->routeService->destroy($route);

        return $this->response->noContent();
    }
}

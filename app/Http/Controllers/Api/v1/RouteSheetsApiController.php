<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\RouteSheetService;
use App\Http\Requests\Api\PaginatedSortedFilteredListRequest;
use App\Http\Requests\Api\SaveRouteSheetRequest;
use App\Models\RouteSheet;
use Dingo\Api\Http\Response;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Saritasa\Transformers\IDataTransformer;
use Throwable;

/**
 * Route sheets requests API controller.
 */
class RouteSheetsApiController extends BaseApiController
{
    /**
     * Route sheets business-logic service.
     *
     * @var RouteSheetService
     */
    private $routeSheetService;

    /**
     * Route sheets requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param RouteSheetService $routeSheetService Route sheets business logic service
     */
    public function __construct(IDataTransformer $transformer, RouteSheetService $routeSheetService)
    {
        parent::__construct($transformer);
        $this->routeSheetService = $routeSheetService;
    }

    /**
     * Returns route sheets list.
     *
     * @param PaginatedSortedFilteredListRequest $request Request with parameters to retrieve paginated sorted filtered
     *     list of items
     *
     * @return Response
     *
     * @throws NotImplementedException
     * @throws InvalidEnumValueException
     */
    public function index(PaginatedSortedFilteredListRequest $request): Response
    {
        $filters = $request->getFilters([
            RouteSheet::COMPANY_ID,
            RouteSheet::ROUTE_ID,
            RouteSheet::BUS_ID,
            RouteSheet::DRIVER_ID,
        ]);

        if ($request->activeFrom()) {
            $filters[] = [RouteSheet::ACTIVE_FROM, '>=', $request->activeFrom()];
        }
        if ($request->activeTo()) {
            $filters[] = [RouteSheet::ACTIVE_TO, '<=', $request->activeTo()];
        }

        return $this->response->paginator(
            $this->routeSheetService->getPageWith(
                $request->getPagingInfo(),
                ['company', 'route', 'driver', 'bus'],
                [],
                $filters,
                $request->getSortOptions()
            ),
            $this->transformer
        );
    }

    /**
     * Stores new route sheet in application.
     *
     * @param SaveRouteSheetRequest $request Request with new route sheet information
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(SaveRouteSheetRequest $request): Response
    {
        $routeSheet = $this->routeSheetService->store($request->getRouteSheetData());

        return $this->response->item($routeSheet, $this->transformer);
    }

    /**
     * Updates route sheet details.
     *
     * @param SaveRouteSheetRequest $request Request with new route sheet information
     * @param RouteSheet $routeSheet Route sheet to update
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(SaveRouteSheetRequest $request, RouteSheet $routeSheet): Response
    {
        $this->routeSheetService->update($routeSheet, $request->getRouteSheetData());

        return $this->response->item($routeSheet, $this->transformer);
    }

    /**
     * Removes route sheet from application.
     *
     * @param RouteSheet $routeSheet Route sheet to delete
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function destroy(RouteSheet $routeSheet): Response
    {
        $this->routeSheetService->destroy($routeSheet);

        return $this->response->noContent();
    }
}

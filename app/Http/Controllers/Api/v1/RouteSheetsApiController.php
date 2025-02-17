<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\RouteSheetEntityService;
use App\Domain\Enums\Abilities;
use App\Domain\Export\RouteSheetsExporter;
use App\Http\Requests\Api\FilteredListRequest;
use App\Http\Requests\Api\PaginatedSortedFilteredListRequest;
use App\Http\Requests\Api\SaveRouteSheetRequest;
use App\Http\Requests\Api\SortedFilteredListRequest;
use App\Models\RouteSheet;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;
use Saritasa\LaravelRepositories\Exceptions\BadCriteriaException;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Saritasa\Transformers\IDataTransformer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

/**
 * Route sheets requests API controller.
 */
class RouteSheetsApiController extends BaseApiController
{
    /**
     * Route sheets entity service.
     *
     * @var RouteSheetEntityService
     */
    private $routeSheetService;

    /**
     * Route sheets exporter.
     *
     * @var RouteSheetsExporter
     */
    private $routeSheetsExporter;

    /**
     * Route sheets requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param RouteSheetEntityService $routeSheetService Route sheets entity service
     * @param RouteSheetsExporter $routeSheetsExporter Route sheets exporter
     */
    public function __construct(
        IDataTransformer $transformer,
        RouteSheetEntityService $routeSheetService,
        RouteSheetsExporter $routeSheetsExporter
    ) {
        parent::__construct($transformer);
        $this->routeSheetService = $routeSheetService;
        $this->routeSheetsExporter = $routeSheetsExporter;
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
     * @throws AuthorizationException
     */
    public function index(PaginatedSortedFilteredListRequest $request): Response
    {
        $this->authorize(Abilities::GET, new RouteSheet());

        $filters = $this->getRouteSheetsFilter($request);

        return $this->response->paginator(
            $this->routeSheetService->getPageWith(
                $request->getPagingInfo(),
                ['company', 'route', 'driver', 'bus'],
                ['transactions'],
                $filters,
                $request->getSortOptions()
            ),
            $this->transformer
        );
    }

    /**
     * Exports route sheets list.
     *
     * @param SortedFilteredListRequest $request Request with parameters to retrieve paginated sorted filtered
     *     list of items
     *
     * @return BinaryFileResponse
     *
     * @throws AuthorizationException
     * @throws InvalidEnumValueException
     * @throws BadCriteriaException
     */
    public function export(SortedFilteredListRequest $request): BinaryFileResponse
    {
        $this->authorize(Abilities::GET, new RouteSheet());

        $filters = $this->getRouteSheetsFilter($request);

        $exportedFileName = $this->routeSheetsExporter->export($filters, $request->getSortOptions());

        return new BinaryFileResponse($exportedFileName);
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
        $routeSheetData = $request->getRouteSheetData();

        $this->authorize(Abilities::CREATE, new RouteSheet($routeSheetData->toArray()));

        $routeSheet = $this->routeSheetService->store($routeSheetData);

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
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(SaveRouteSheetRequest $request, RouteSheet $routeSheet): Response
    {
        $this->authorize(Abilities::UPDATE, $routeSheet);

        if (!$routeSheet->editable()) {
            $this->deny(trans('errors.routSheetNotEditable'));
        }

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
     * @throws Throwable
     */
    public function destroy(RouteSheet $routeSheet): Response
    {
        $this->authorize(Abilities::DELETE, $routeSheet);

        if (!$routeSheet->editable()) {
            $this->deny(trans('errors.routSheetNotEditable'));
        }

        $this->routeSheetService->destroy($routeSheet);

        return $this->response->noContent();
    }

    /**
     * Retrieves route sheets filter details from request.
     *
     * @param FilteredListRequest $request Request to retrieve filter details from
     *
     * @return string[]
     */
    private function getRouteSheetsFilter(FilteredListRequest $request): array
    {
        $filters = $request->getFilters([
            RouteSheet::COMPANY_ID,
            RouteSheet::ROUTE_ID,
            RouteSheet::BUS_ID,
            RouteSheet::DRIVER_ID,
            RouteSheet::AUTOGENERATED,
        ]);

        if ($request->activeFrom()) {
            $filters[] = [RouteSheet::ACTIVE_FROM, '>=', $request->activeFrom()];
        }
        if ($request->activeTo()) {
            $filters[] = [RouteSheet::ACTIVE_TO, '<=', $request->activeTo()];
        }

        if ($this->singleCompanyUser()) {
            $filters[] = [RouteSheet::COMPANY_ID, '=', $this->user->company_id];
        }

        return $filters;
    }
}

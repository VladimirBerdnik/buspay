<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Services\RouteSheetService;
use App\Http\Requests\Api\PaginatedSortedFilteredListRequest;
use Dingo\Api\Http\Response;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;
use Saritasa\Transformers\IDataTransformer;

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
        $filters = $request->getFilters();

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
}

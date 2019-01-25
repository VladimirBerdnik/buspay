<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\ReplenishmentEntityService;
use App\Domain\Enums\Abilities;
use App\Http\Requests\Api\PaginatedSortedFilteredListRequest;
use App\Models\Replenishment;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;
use Saritasa\Transformers\IDataTransformer;

/**
 * Replenishments requests API controller.
 */
class ReplenishmentsApiController extends BaseApiController
{
    /**
     * Replenishments entity service.
     *
     * @var ReplenishmentEntityService
     */
    private $replenishmentService;

    /**
     * Replenishments requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param ReplenishmentEntityService $replenishmentService Replenishments entity service
     */
    public function __construct(IDataTransformer $transformer, ReplenishmentEntityService $replenishmentService)
    {
        parent::__construct($transformer);
        $this->replenishmentService = $replenishmentService;
    }

    /**
     * Returns replenishments list.
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
        $this->authorize(Abilities::GET, new Replenishment());

        $filters = $request->getFilters([]);
        $searchString = $request->getSearchString();
        if ($searchString) {
            $filters[] = [
                [
                    [Replenishment::EXTERNAL_ID, '=', $searchString, 'or'],
                    [Replenishment::AMOUNT, '=', $searchString, 'or'],
                ],
            ];
        }

        return $this->response->paginator(
            $this->replenishmentService->getPageWith(
                $request->getPagingInfo(),
                ['card'],
                [],
                $filters,
                $request->getSortOptions()
            ),
            $this->transformer
        );
    }
}

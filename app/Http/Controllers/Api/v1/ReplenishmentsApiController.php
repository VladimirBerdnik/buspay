<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\CardEntityService;
use App\Domain\EntitiesServices\ReplenishmentEntityService;
use App\Domain\Enums\Abilities;
use App\Http\Requests\Api\PaginatedSortedFilteredListRequest;
use App\Models\Card;
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
     * Replenishment entities business logic service.
     *
     * @var ReplenishmentEntityService
     */
    private $replenishmentEntityService;

    /**
     * Card entities service.
     *
     * @var CardEntityService
     */
    private $cardEntityService;

    /**
     * Replenishments requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param ReplenishmentEntityService $replenishmentEntityService Replenishment entities business logic service
     * @param CardEntityService $cardEntityService Card entities service
     */
    public function __construct(
        IDataTransformer $transformer,
        ReplenishmentEntityService $replenishmentEntityService,
        CardEntityService $cardEntityService
    ) {
        parent::__construct($transformer);
        $this->replenishmentEntityService = $replenishmentEntityService;
        $this->cardEntityService = $cardEntityService;
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

        $filters = $request->getFilters();

        $searchString = $request->getSearchString();
        if ($searchString) {
            /**
             * Card found by number that equals search string.
             *
             * @var Card $card
             */
            $card = $this->cardEntityService->findWhere([Card::CARD_NUMBER => $searchString]);
            if ($card) {
                $filters[] = [Replenishment::CARD_ID, '=', $card->id];
            } else {
                // Dirty hack that allows to retrieve empty results
                $filters[] = [Replenishment::ID, '=', -1];
            }
        }
        if ($request->activeFrom()) {
            $filters[] = [Replenishment::REPLENISHED_AT, '>=', $request->activeFrom()];
        }
        if ($request->activeTo()) {
            $filters[] = [Replenishment::REPLENISHED_AT, '<=', $request->activeTo()];
        }

        return $this->response->paginator(
            $this->replenishmentEntityService->getPageWith(
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

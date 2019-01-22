<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\CardEntityService;
use App\Domain\Enums\Abilities;
use App\Domain\Enums\CardTypesIdentifiers;
use App\Http\Requests\Api\PaginatedSortedFilteredListRequest;
use App\Models\Card;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;

/**
 * Cards requests API controller.
 */
class CardsApiController extends BaseApiController
{
    /**
     * Cards entity service.
     *
     * @var CardEntityService
     */
    private $cardService;

    /**
     * Cards requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param CardEntityService $cardService Cards entity service
     */
    public function __construct(IDataTransformer $transformer, CardEntityService $cardService)
    {
        parent::__construct($transformer);
        $this->cardService = $cardService;
    }

    /**
     * Returns cards list.
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
        $this->authorize(Abilities::GET, new Card());

        $filters = $request->getFilters([Card::CARD_TYPE_ID]);
        $searchString = $request->getSearchString();
        if ($searchString) {
            $filters[] = [
                [
                    [Card::UIN, 'like', "%{$searchString}%", 'or'],
                    [Card::CARD_NUMBER, 'like', "%{$searchString}%", 'or'],
                ],
            ];
        }

        return $this->response->paginator(
            $this->cardService->getPageWith(
                $request->getPagingInfo(),
                ['cardType'],
                [],
                $filters,
                $request->getSortOptions()
            ),
            $this->transformer
        );
    }

    /**
     * Returns cards list with driver card type.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     * @throws AuthorizationException
     */
    public function driverCards(): Response
    {
        $this->authorize(Abilities::GET_DRIVERS_CARDS, new Card());
        $this->transformer->setDefaultIncludes([]);

        return $this->response->collection(
            $this->cardService->getWith(
                [],
                [],
                [
                    Card::CARD_TYPE_ID => CardTypesIdentifiers::DRIVER,
                    Card::ACTIVE => true,
                ],
                new SortOptions(Card::CARD_NUMBER)
            ),
            $this->transformer
        );
    }
}

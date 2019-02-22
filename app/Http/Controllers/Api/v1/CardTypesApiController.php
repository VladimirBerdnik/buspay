<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\CardTypeEntityService;
use App\Domain\Enums\Abilities;
use App\Models\Card;
use App\Models\CardType;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;

/**
 * Card types requests API controller.
 */
class CardTypesApiController extends BaseApiController
{
    /**
     * Card types entity service.
     *
     * @var CardTypeEntityService
     */
    private $cardTypeService;

    /**
     * Card types requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param CardTypeEntityService $cardTypeService Card types entity service
     */
    public function __construct(IDataTransformer $transformer, CardTypeEntityService $cardTypeService)
    {
        parent::__construct($transformer);
        $this->cardTypeService = $cardTypeService;
    }

    /**
     * Returns card types list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize(Abilities::GET, new CardType());

        return $this->response->collection(
            $this->cardTypeService->getWith(
                [],
                $this->can(Abilities::GET, new Card()) ? ['cards'] : [],
                [],
                new SortOptions(CardType::ID)
            ),
            $this->transformer
        );
    }
}

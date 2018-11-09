<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Enums\CardTypesIdentifiers;
use App\Domain\Services\CardService;
use App\Models\Card;
use Dingo\Api\Http\Response;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;

/**
 * Cards requests API controller.
 */
class CardsApiController extends BaseApiController
{
    /**
     * Cards business-logic service.
     *
     * @var CardService
     */
    private $cardService;

    /**
     * Cards requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param CardService $cardService Cards business logic service
     */
    public function __construct(IDataTransformer $transformer, CardService $cardService)
    {
        parent::__construct($transformer);
        $this->cardService = $cardService;
    }

    /**
     * Returns cards list with driver card type.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     */
    public function driverCards(): Response
    {
        $this->transformer->setDefaultIncludes([]);

        return $this->response->collection(
            $this->cardService->getWith(
                [],
                [],
                [Card::CARD_TYPE_ID => CardTypesIdentifiers::DRIVER],
                new SortOptions(Card::CARD_NUMBER)
            ),
            $this->transformer
        );
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Services\CardTypeService;
use App\Models\CardType;
use Dingo\Api\Http\Response;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;

/**
 * Card types requests API controller.
 */
class CardTypesApiController extends BaseApiController
{
    /**
     * Card types business-logic service.
     *
     * @var CardTypeService
     */
    private $cardTypeService;

    /**
     * Card types requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param CardTypeService $cardTypeService Card types business logic service
     */
    public function __construct(IDataTransformer $transformer, CardTypeService $cardTypeService)
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
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->cardTypeService->getWith([], [], [], new SortOptions(CardType::ID)),
            $this->transformer
        );
    }
}

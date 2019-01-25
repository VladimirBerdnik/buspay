<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\CardEntityService;
use App\Domain\Services\CardBalanceService;
use App\Models\Card;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Saritasa\Transformers\IDataTransformer;

/**
 * Card balance requests API controller.
 */
class CardBalanceApiController extends BaseApiController
{
    /**
     * Allows to retrieve card balance total, replenishment adn write-off records.
     *
     * @var CardBalanceService
     */
    private $cardBalanceService;

    /**
     * Cards entities service.
     *
     * @var CardEntityService
     */
    private $cardEntityService;

    /**
     * Card balance requests API controller.
     *
     * @param CardEntityService $cardEntityService Cards entities service
     * @param CardBalanceService $cardBalanceService Allows to retrieve card balance total, replenishment adn write-off
     *     records
     * @param IDataTransformer|null $transformer Default responses transformer
     */
    public function __construct(
        CardEntityService $cardEntityService,
        CardBalanceService $cardBalanceService,
        ?IDataTransformer $transformer = null
    ) {
        parent::__construct($transformer);
        $this->cardBalanceService = $cardBalanceService;
        $this->cardEntityService = $cardEntityService;
    }

    /**
     * Returns card replenishment and write-off totals.
     *
     * @param string $cardNumber Card number for which need to retrieve totals
     *
     * @return JsonResponse
     */
    public function total(string $cardNumber): JsonResponse
    {
        /**
         * Requested card for which need to retrieve totals.
         *
         * @var Card $card
         */
        $card = $this->cardEntityService->findWhere([Card::CARD_NUMBER => $cardNumber]);
        if (!$card) {
            throw (new ModelNotFoundException())->setModel(Card::class, $cardNumber);
        }

        return response()->json(['total' => $this->cardBalanceService->getTotal($card)]);
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\CardEntityService;
use App\Domain\Services\CardBalanceService;
use App\Models\Card;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Saritasa\Exceptions\InvalidEnumValueException;
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
     * Retrieves card by card number.
     *
     * @param string $cardNumber Card number by which need to retrieve card
     *
     * @return Card
     */
    private function getCard(string $cardNumber): Card
    {
        /**
         * Requested card for which need to retrieve totals.
         *
         * @var Card $card
         */
        $card = $this->cardEntityService->findWhere([Card::CARD_NUMBER => $cardNumber]);

        if (!$card) {
            throw new ModelNotFoundException(trans('errors.cardByNumberNotFound'));
        }

        return $card;
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
        $card = $this->getCard($cardNumber);

        return response()->json(['total' => $this->cardBalanceService->getTotal($card)]);
    }

    /**
     * Returns transactions for card with given card number.
     *
     * @param string $cardNumber Card number for which need to retrieve transactions
     *
     * @return Response
     *
     * @throws InvalidEnumValueException
     */
    public function transactions(string $cardNumber): Response
    {
        $card = $this->getCard($cardNumber);

        $transactions = $this->cardBalanceService->getTransactions($card);

        return $this->response->collection($transactions, $this->transformer);
    }
}

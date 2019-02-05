<?php

namespace App\Http\Transformers\Api;

use App\Models\Card;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms card to display details.
 */
class CardTransformer extends BaseTransformer
{
    public const INCLUDE_CARD_TYPE = 'cardType';

    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        self::INCLUDE_CARD_TYPE,
    ];

    /**
     * Transforms card type to display details.
     *
     * @var CardTypeTransformer
     */
    private $cardTypeTransformer;

    /**
     * Transforms card to display details.
     *
     * @param CardTypeTransformer $cardTypeTransformer Transforms card type to display details
     */
    public function __construct(CardTypeTransformer $cardTypeTransformer)
    {
        $this->cardTypeTransformer = $cardTypeTransformer;

        $this->cardTypeTransformer->setDefaultIncludes([]);
    }

    /**
     * Transforms card to display details.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Card) {
            throw new TransformTypeMismatchException($this, Card::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param Card $card Card to transform
     *
     * @return string[]
     */
    protected function transformModel(Card $card): array
    {
        return [
            Card::ID => $card->id,
            Card::CARD_TYPE_ID => $card->card_type_id,
            Card::UIN => $card->uin,
            Card::CARD_NUMBER => $card->card_number,
            Card::ACTIVE => $card->active,
            Card::SYNCHRONIZED_AT => $card->synchronized_at ? $card->synchronized_at->toIso8601String() : null,
        ];
    }

    /**
     * Transforms card type to display details.
     *
     * @return CardTypeTransformer
     */
    public function getCardTypeTransformer(): CardTypeTransformer
    {
        return $this->cardTypeTransformer;
    }

    /**
     * Includes card type into transformed response.
     *
     * @param Card $card Card to retrieve card type details
     *
     * @return ResourceInterface
     */
    protected function includeCardType(Card $card): ResourceInterface
    {
        if (!$card->cardType) {
            return $this->null();
        }

        return $this->item($card->cardType, $this->cardTypeTransformer);
    }
}

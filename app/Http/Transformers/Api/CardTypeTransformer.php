<?php

namespace App\Http\Transformers\Api;

use App\Models\CardType;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms card type to display details.
 */
class CardTypeTransformer extends BaseTransformer
{
    /**
     * Transforms card type to display details.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof CardType) {
            throw new TransformTypeMismatchException($this, CardType::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param CardType $cardType Card type to transform
     *
     * @return string[]
     */
    protected function transformModel(CardType $cardType): array
    {
        return [
            CardType::ID => $cardType->id,
            CardType::NAME => $cardType->name,
            // possible related records count
            'cards_count' => $cardType->getAttribute('cards_count'),
        ];
    }
}

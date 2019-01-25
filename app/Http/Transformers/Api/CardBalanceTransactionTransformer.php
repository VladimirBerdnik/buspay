<?php

namespace App\Http\Transformers\Api;

use App\Domain\Dto\CardBalanceTransactionData;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms card balance transaction to display details.
 */
class CardBalanceTransactionTransformer extends BaseTransformer
{
    /**
     * Transforms card balance transaction to display details.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof CardBalanceTransactionData) {
            throw new TransformTypeMismatchException($this, CardBalanceTransactionData::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param CardBalanceTransactionData $balanceTransactionData Card balance transaction to transform
     *
     * @return string[]
     */
    protected function transformModel(CardBalanceTransactionData $balanceTransactionData): array
    {
        return [
            CardBalanceTransactionData::DATE => $balanceTransactionData->date->toIso8601String(),
            CardBalanceTransactionData::TYPE => $balanceTransactionData->type,
            CardBalanceTransactionData::AMOUNT => $balanceTransactionData->amount,
        ];
    }
}
